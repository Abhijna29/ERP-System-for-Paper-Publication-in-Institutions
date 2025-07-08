<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\ResearchPaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SearchPaperController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $role = Auth::user()->role;
        $crossRefPage = (int) $request->input('crossref_page', 1);
        $crossRefPerPage = 10;
        $offset = ($crossRefPage - 1) * $crossRefPerPage;

        // 1. Local DB Search (same as before)
        $localPapers = ResearchPaper::where(function ($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
                ->orWhere('abstract', 'like', "%{$query}%")
                ->orWhere('keywords', 'like', "%{$query}%");
        })
            ->latest()
            ->paginate(5)
            ->appends(['query' => $query, 'crossref_page' => $crossRefPage]);

        // 2. CrossRef API
        $crossRefResults = [];
        $crossRefTotalResults = 0;

        try {
            $response = Http::get('https://api.crossref.org/works', [
                'query' => $query,
                'rows' => $crossRefPerPage,
                'offset' => $offset,
            ]);

            $crossRefData = $response->json();

            if (isset($crossRefData['message']['items'])) {
                $crossRefResults = collect($crossRefData['message']['items'])->map(function ($item) {
                    return [
                        'title' => $item['title'][0] ?? 'No title',
                        'authors' => isset($item['author'])
                            ? collect($item['author'])->map(fn($a) => ($a['given'] ?? '') . ' ' . ($a['family'] ?? ''))->implode(', ')
                            : 'Unknown',
                        'published' => $item['issued']['date-parts'][0][0] ?? 'N/A',
                        'doi' => $item['DOI'] ?? '',
                        'url' => $item['URL'] ?? '#',
                        'source' => 'CrossRef'
                    ];
                })->toArray();

                $crossRefTotalResults = $crossRefData['message']['total-results'] ?? 0;
            }
        } catch (\Exception $e) {
            $crossRefResults = [];
        }

        return view('dashboard.search_papers.searchResults', compact(
            'query',
            'localPapers',
            'crossRefResults',
            'crossRefPage',
            'crossRefPerPage',
            'crossRefTotalResults',
            'role'
        ));
    }

    public function show($id)
    {
        $paper = ResearchPaper::with('user', 'category', 'subCategory', 'childCategory')->findOrFail($id);
        $role = Auth::user()->role;
        $alreadyDownloaded = Download::where('user_id', Auth::id())
            ->where('research_paper_id', $paper->id)
            ->exists();

        // Optional: add any authorization if needed, e.g., only allow users to view papers they are allowed to see

        return view('dashboard.search_papers.showPaper', compact('paper', 'role', 'alreadyDownloaded'));
    }
}
