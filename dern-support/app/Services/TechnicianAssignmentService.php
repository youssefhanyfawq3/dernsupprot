<?php

namespace App\Services;

use App\Models\Technician;
use App\Models\ServiceSchedule;
use Carbon\Carbon;

class TechnicianAssignmentService
{
    public function assignTechnician(ServiceSchedule $schedule)
    {
        // Get available technicians based on service type and area
        $availableTechnicians = Technician::where('is_available', true)
            ->where(function($query) use ($schedule) {
                $query->where('service_area', $schedule->service_type)
                    ->orWhere('service_area', 'both');
            })
            ->whereDoesntHave('schedules', function($query) use ($schedule) {
                $query->where('scheduled_at', $schedule->scheduled_at)
                    ->where('status', '!=', 'cancelled');
            })
            ->get();

        if ($availableTechnicians->isEmpty()) {
            return null;
        }

        // Sort technicians by workload
        $technicians = $availableTechnicians->sortBy(function($technician) {
            return $technician->schedules()
                ->where('scheduled_at', '>=', Carbon::now())
                ->where('status', '!=', 'cancelled')
                ->count();
        });

        // Assign the technician with the least workload
        $assignedTechnician = $technicians->first();
        $schedule->technician_id = $assignedTechnician->id;
        $schedule->save();

        return $assignedTechnician;
    }
}
