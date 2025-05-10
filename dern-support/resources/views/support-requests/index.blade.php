<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Support Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('support-requests.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Support Request
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">Title</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">Status</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">Created</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($supportRequests as $request)
                                    <tr>
                                        <td class="px-6 py-4">{{ $request->title }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $request->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($request->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-gray-100 text-gray-800') }}">
                                                {{ str_replace('_', ' ', ucfirst($request->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $request->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('support-requests.show', $request) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
