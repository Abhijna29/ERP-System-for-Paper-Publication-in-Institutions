<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::all();
        return view('dashboard.admin.createSubscription', compact('plans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'duration' => 'required|string',
            'price' => 'required|numeric',
            'objective' => 'required|string|max:255',
            'summary' => 'required|string|max:255',
            'paper_limit' => 'required|integer|min:0',
            'download_limit' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        SubscriptionPlan::create($validator->validated());

        if ($request->expectsJson()) {
            return response()->json(['success' => 'Plan created successfully.'], 200);
        }

        return redirect()->back()->with('success', 'Plan created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'duration' => 'required|string',
            'price' => 'required|numeric',
            'objective' => 'nullable|string|max:255',
            'summary' => 'nullable|string|max:255',
            'paper_limit' => 'required|string|min:0',
            'download_limit' => 'required|string|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $plan = SubscriptionPlan::findOrFail($id);
        $plan->update($validator->validated());

        if ($request->expectsJson()) {
            return response()->json(['success' => 'Plan updated successfully.'], 200);
        }

        return redirect()->back()->with('success', 'Plan updated successfully.');
    }

    public function destroy($id)
    {
        SubscriptionPlan::findOrFail($id)->delete();

        return response()->json(['success' => 'Plan deleted successfully.'], 200);
    }
}
