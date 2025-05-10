<?php

namespace App\Http\Controllers;

use App\Models\ServiceSchedule;
use App\Models\Technician;
use Illuminate\Http\Request;

class ServiceScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'support_request_id' => 'required|exists:support_requests,id',
            'service_type' => 'required|in:on_site,in_office,courier_pickup',
            'scheduled_at' => 'required|date|after:now',
            'location' => 'required_if:service_type,on_site',
        ]);

        // Find available technician if not courier pickup
        $technician_id = null;
        if ($validated['service_type'] !== 'courier_pickup') {
            $technician = Technician::where('service_area', 'both')
                ->orWhere('service_area', $validated['service_type'])
                ->where('is_available', true)
                ->whereDoesntHave('schedules', function ($query) use ($validated) {
                    $query->where('scheduled_at', $validated['scheduled_at'])
                          ->where('status', '!=', 'cancelled');
                })
                ->first();

            if ($technician) {
                $technician_id = $technician->id;
            }
        }

        $schedule = ServiceSchedule::create([
            'support_request_id' => $validated['support_request_id'],
            'technician_id' => $technician_id,
            'service_type' => $validated['service_type'],
            'scheduled_at' => $validated['scheduled_at'],
            'location' => $validated['location'] ?? null,
        ]);

        return redirect()
            ->route('support-requests.show', $validated['support_request_id'])
            ->with('success', 'Service has been scheduled successfully.');
    }

    public function update(Request $request, ServiceSchedule $schedule)
    {
        if (!auth()->user()->is_staff) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
        ]);

        $schedule->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Schedule status updated successfully.');
    }
}
