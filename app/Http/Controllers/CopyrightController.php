<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Copyright;

class CopyrightController extends Controller
{
    public function index()
    {
        $copyrights = Copyright::where('user_id', Auth::id())->get();
        return view('dashboard.researcher.copyright', compact('copyrights'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type_of_work' => 'required|string',
            'registration_number' => 'required|string',
            'registration_date' => 'required|date',
        ]);

        Copyright::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'type_of_work' => $request->type_of_work,
            'registration_number' => $request->registration_number,
            'registration_date' => $request->registration_date,
            'status' => 'filed',
        ]);

        return redirect()->route('researcher.copyrights.index')->with('success', 'Work submitted successfully.');
    }

    public function uploadCertificate(Request $request, $id)
    {
        $request->validate([
            'certificate' => 'required|file|mimes:pdf|max:2048',
        ]);

        $copyright = Copyright::where('user_id', Auth::id())->findOrFail($id);
        $certificatePath = $request->file('certificate')->store('copyright_certificates', 'public');

        $copyright->update([
            'certificate_path' => $certificatePath,
        ]);

        return back()->with('success', 'Certificate uploaded successfully.');
    }
    public function adminIndex()
    {
        $copyrights = Copyright::with('user')->latest()->get();
        return view('dashboard.admin.intellectualProperty.copyright', compact('copyrights'));
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:filed,registered'
        ]);

        $copyright = Copyright::findOrFail($id);
        $copyright->status = $request->status;
        $copyright->save();

        return redirect()->back()->with('success', 'Trademark status updated.');
    }
}
