<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use Illuminate\Http\Request;

class SupportRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supportRequests = auth()->user()->is_staff 
            ? SupportRequest::with('user')->latest()->get()
            : auth()->user()->supportRequests()->latest()->get();

        return view('support-requests.index', compact('supportRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('support-requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'service_type' => 'required|string|in:Hardware,Software,Network,Other',
            'priority' => 'required|string|in:low,medium,high',
        ]);

        $validated['status'] = 'open';
        $supportRequest = auth()->user()->supportRequests()->create($validated);

        return redirect()
            ->route('support-requests.show', $supportRequest)
            ->with('success', 'Support request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SupportRequest $supportRequest)
    {
        if (!auth()->user()->is_staff && auth()->id() !== $supportRequest->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $supportRequest->load(['user', 'technician', 'comments.user', 'satisfactionRating']);
        $technicians = auth()->user()->is_staff ? \App\Models\Technician::where('is_available', true)->get() : null;

        return view('support-requests.show', compact('supportRequest', 'technicians'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupportRequest $supportRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupportRequest $supportRequest)
    {
        if (!auth()->user()->is_staff) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'technician_id' => 'sometimes|exists:technicians,id',
        ]);

        if (isset($validated['status']) && $validated['status'] === 'resolved' && $supportRequest->status !== 'resolved') {
            $validated['resolved_at'] = now();
        }

        $supportRequest->update($validated);

        return redirect()
            ->route('support-requests.show', $supportRequest)
            ->with('success', 'Support request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupportRequest $supportRequest)
    {
        //
    }
}
