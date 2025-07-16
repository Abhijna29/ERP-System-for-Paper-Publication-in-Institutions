<?php

namespace App\Http\Controllers\Researcher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patent;
use App\Models\Patents;
use App\Models\ResearchPaper;
use App\Models\User;
use App\Notifications\PatentCertificateUploaded;
use Illuminate\Support\Facades\Auth;

class PatentsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'investors_name' => 'required|string|max:255',
            'work_title' => 'required|string',
            'work_description' => 'required|string',
            'year' => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 1),
            'certificate' => 'nullable|file|mimes:pdf|max:2048',
            'research_paper_id' => 'nullable|exists:research_papers,id'
        ]);

        $patents = Patents::create([
            'user_id' => Auth::id(),
            'investors_name' => $request->investors_name,
            'work_title' => $request->work_title,
            'work_description' => $request->work_description,
            'year' => $request->year,
            'type' => 'filed',
            'research_paper_id' => $request->research_paper_id,
        ]);
        return redirect()->route('researcher.patents.index')->with('success', 'Patent filed successfully.');
    }

    public function index(Request $request)
    {
        $papers = ResearchPaper::where('user_id', Auth::id())->get();
        $patents = Patents::where('user_id', Auth::id())->get();
        return view('dashboard.researcher.patents', compact('patents', 'papers'));
    }

    public function uploadCertificate(Request $request, $id)
    {
        $request->validate([
            'certificate' => 'required|file|mimes:pdf|max:2048',
        ]);

        $patent = Patents::where('user_id', Auth::id())->findOrFail($id);

        $path = $request->file('certificate')->store('patent_certificates', 'public');

        $patent->update([
            'certificate_path' => $path,
        ]);
        $admin = User::where('role', 'admin')->first();
        $admin->notify(new PatentCertificateUploaded($patent));

        return redirect()->back()->with('success', 'Certificate uploaded successfully.');
    }

    public function markAsPublished(Request $request, $id)
    {
        $request->validate([
            'publication_number' => 'required|string|max:100'
        ]);

        $patent = Patents::where('user_id', Auth::id())->findOrFail($id);
        $patent->update([
            'publication_number' => $request->publication_number,
            'type' => 'published'
        ]);

        return redirect()->back()->with('success', 'Patent marked as published.');
    }
}
