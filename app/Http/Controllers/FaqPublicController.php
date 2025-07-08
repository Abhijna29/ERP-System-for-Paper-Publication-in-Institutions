<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Support\Facades\Auth;

class FaqPublicController extends Controller
{
    public function index()
    {
        $faqs = Faq::latest()->get();
        $role = Auth::user()->role;
        $faqs = Faq::whereNull('role')
            ->orWhere('role', $role)
            ->latest()
            ->get();
        return view('faqs.index', compact('faqs', 'role'));
    }
}
