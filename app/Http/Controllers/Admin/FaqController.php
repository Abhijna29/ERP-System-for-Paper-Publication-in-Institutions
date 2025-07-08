<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class FaqController extends Controller
{
    public function faq()
    {
        return view('dashboard.admin.faq');
    }

    public function fetch()
    {
        return response()->json(Faq::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'role' => 'nullable|string|in:researcher,reviewer,institution,department',
        ]);

        $faq = Faq::create($request->only('title', 'description', 'role'));

        return response()->json(['message' => 'FAQ created successfully.', 'faq' => $faq]);
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'role' => 'nullable|string|in:researcher,reviewer,institution,department',
        ]);

        $faq->update($request->only('title', 'description', 'role'));

        return response()->json(['message' => 'FAQ updated successfully.']);
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->json(['message' => 'FAQ deleted successfully.']);
    }
}
