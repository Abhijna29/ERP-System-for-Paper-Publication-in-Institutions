<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patent;
use App\Models\Patents;

class AdminPatentController extends Controller
{
    public function index()
    {
        $patents = Patents::latest()->get();
        return view('dashboard.admin.intellectualProperty.patents.index', compact('patents'));
    }

    public function edit($id)
    {
        $patent = Patents::findOrFail($id);
        return view('dashboard.admin.intellectualProperty.patents.edit', compact('patent'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:filed,published,granted',
            'number' => 'nullable|string',
            'grant_number' => 'nullable|string',
        ]);

        $patent = Patents::findOrFail($id);
        $patent->update([
            'type' => $request->type,
            'number' => $request->number,
            'grant_number' => $request->grant_number,
        ]);

        return redirect()->route('admin.patents.index')->with('success', 'Patent updated.');
    }
}
