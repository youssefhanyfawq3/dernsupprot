<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 dark:text-gray-400 text-sm">Total Requests</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $metrics['total_requests'] }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 dark:text-gray-400 text-sm">Open Requests</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $metrics['open_requests'] }}</div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 dark:text-gray-400 text-sm">Average Satisfaction</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($metrics['avg_satisfaction'], 1) }}/5
                        </div>
                    </div>
                </div>
            </div>

            @if($isStaff)
                <!-- Staff Specific Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Priority Distribution -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Requests by Priority</h3>
                            <div class="space-y-4">
                                @foreach($metrics['requests_by_priority'] as $priority => $count)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 dark:text-gray-300 capitalize">{{ $priority }}</span>
                                        <span class="px-3 py-1 rounded-full text-sm
                                            @if($priority == 'high') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @elseif($priority == 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @endif">
                                            {{ $count }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Technician Workload -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Technician Workload</h3>
                            <div class="space-y-4">
                                @foreach($metrics['technician_workload'] as $technician)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 dark:text-gray-300">{{ $technician->name }}</span>
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full text-sm">
                                            {{ $technician->active_requests_count }} active
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Resolution Time -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Average Resolution Time</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($metrics['avg_resolution_time'], 1) }} hours
                        </p>
                    </div>
                </div>
            @else
                <!-- Customer Specific Content -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">My Recent Requests</h3>
                        <div class="space-y-4">
                            @foreach($metrics['my_recent_requests'] as $request)
                                <div class="flex items-center justify-between border-b dark:border-gray-700 pb-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $request->title }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Status: <span class="capitalize">{{ $request->status }}</span>
                                        </p>
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $request->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
