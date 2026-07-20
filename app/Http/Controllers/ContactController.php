<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('frontend.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        Lead::create([
            ...$validated,
            'type' => 'contact',
            'source' => 'contact_form',
            'status' => 'new',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Thank you! We will contact you shortly.');
    }

    public function quote(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'product' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:2000',
        ]);

        Lead::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'company' => $validated['company'] ?? null,
            'message' => $validated['message'] ?? null,
            'type' => 'quote',
            'source' => 'quote_form',
            'status' => 'new',
            'form_data' => ['product' => $validated['product'] ?? null],
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Quote request submitted successfully!');
    }
}
