<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use App\Models\SatisfactionRating;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $isStaff = $request->user()->is_staff;

        // Common metrics
        $metrics = [
            'total_requests' => SupportRequest::count(),
            'open_requests' => SupportRequest::where('status', 'open')->count(),
            'avg_satisfaction' => SatisfactionRating::avg('rating'),
        ];

        if ($isStaff) {
            // Staff-specific metrics
            $metrics += [
                'requests_by_priority' => SupportRequest::groupBy('priority')
                    ->select('priority', DB::raw('count(*) as count'))
                    ->pluck('count', 'priority'),
                'technician_workload' => Technician::withCount('activeRequests')->get(),
                'avg_resolution_time' => SupportRequest::whereNotNull('resolved_at')
                    ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_time')
                    ->value('avg_time'),
            ];
        } else {
            // Customer-specific metrics
            $metrics += [
                'my_open_requests' => SupportRequest::where('user_id', $request->user()->id)
                    ->where('status', 'open')
                    ->count(),
                'my_recent_requests' => SupportRequest::where('user_id', $request->user()->id)
                    ->latest()
                    ->take(5)
                    ->get(),
            ];
        }

        return view('dashboard', compact('metrics', 'isStaff'));
    }
}
