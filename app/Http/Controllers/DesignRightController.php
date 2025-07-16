<?php

namespace App\Http\Controllers;

use App\Models\DesignRight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignRightController extends Controller
{
    public function index()
    {
        $designs = DesignRight::where('user_id', Auth::id())->get();
        return view('dashboard.researcher.designs', compact('designs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'design_class' => 'required|string',
            'registration_date' => 'nullable|date',
            'design_file' => 'required|file|mimes:png,jpg,pdf|max:2048',
        ]);

        $path = $request->file('design_file')->store('design_files', 'public');

        DesignRight::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'design_class' => $request->design_class,
            'registration_date' => $request->registration_date,
            'design_file_path' => $path,
        ]);

        return redirect()->route('designs.index')->with('success', 'Design submitted.');
    }

    public function adminIndex()
    {
        $designs = DesignRight::with('user')->get();
        return view('dashboard.admin.intellectualProperty.designs', compact('designs'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,under_review,approved,rejected']);
        DesignRight::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }

    public function uploadCertificate(Request $request, $id)
    {
        $request->validate([
            'certificate' => 'required|file|mimes:pdf|max:2048',
        ]);

        $path = $request->file('certificate')->store('design_certificates', 'public');

        DesignRight::findOrFail($id)->update([
            'certificate_path' => $path,
        ]);

        return back()->with('success', 'Certificate uploaded.');
    }
}
