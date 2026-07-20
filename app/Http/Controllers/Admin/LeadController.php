<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lead::with('assignee')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->paginate(15);
        $statuses = ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost'];

        return view('admin.leads.index', compact('leads', 'statuses'));
    }

    public function show(Lead $lead): View
    {
        $users = User::role(['sales-team', 'admin', 'super-admin'])->get();

        return view('admin.leads.show', compact('lead', 'users'));
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,proposal,won,lost',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'reminder_at' => 'nullable|date',
        ]);

        $lead->update($validated);

        return redirect()->route('admin.leads.show', $lead)->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted successfully.');
    }
}
