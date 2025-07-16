<?php

// app/Http/Controllers/TrademarkController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trademarks;
use Illuminate\Support\Facades\Auth;

class TrademarksController extends Controller
{
    // Researcher view
    public function index()
    {
        $trademarks = Trademarks::where('user_id', Auth::id())->get();
        return view('dashboard.researcher.trademarks', compact('trademarks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'application_number' => 'nullable|string|max:100',
            'application_date' => 'nullable|date',
            'description' => 'nullable|string',
            'certificate' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $data = $request->only(['title', 'application_number', 'application_date', 'description']);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('certificate')) {
            $data['certificate_path'] = $request->file('certificate')->store('trademark_certificates', 'public');
        }

        Trademarks::create($data);

        return redirect()->back()->with('success', 'Trademark submitted successfully.');
    }

    // Admin view
    public function adminIndex()
    {
        $trademarks = Trademarks::with('user')->latest()->get();
        return view('dashboard.admin.intellectualProperty.trademarks', compact('trademarks'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:filed,registered,rejected'
        ]);

        $trademark = Trademarks::findOrFail($id);
        $trademark->status = $request->status;
        $trademark->save();

        return redirect()->back()->with('success', 'Trademark status updated.');
    }
}
