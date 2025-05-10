<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Support Request Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium">{{ $supportRequest->title }}</h3>
                        <div class="mt-2 space-y-2">
                            <div class="flex space-x-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $supportRequest->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($supportRequest->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                       'bg-gray-100 text-gray-800') }}">
                                    {{ str_replace('_', ' ', ucfirst($supportRequest->status)) }}
                                </span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $supportRequest->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                       ($supportRequest->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                                       ($supportRequest->priority === 'medium' ? 'bg-blue-100 text-blue-800' : 
                                       'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($supportRequest->priority) }} Priority
                                </span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    Service Type: {{ str_replace('_', ' ', ucfirst($supportRequest->service_required)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="prose dark:prose-invert max-w-none">
                        {{ $supportRequest->description }}
                    </div>

                    <!-- Service Schedule Section -->
                    @if(!$supportRequest->schedule)
                        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-8">
                            <h4 class="text-lg font-medium mb-4">{{ __('Schedule Service') }}</h4>
                            <form method="POST" action="{{ route('service-schedules.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="support_request_id" value="{{ $supportRequest->id }}">
                                
                                <div>
                                    <x-input-label for="scheduled_at" :value="__('Preferred Date and Time')" />
                                    <x-text-input id="scheduled_at" name="scheduled_at" type="datetime-local" 
                                        class="mt-1 block w-full" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('scheduled_at')" />
                                </div>

                                @if($supportRequest->service_required === 'on_site')
                                    <div>
                                        <x-input-label for="location" :value="__('Service Location')" />
                                        <textarea id="location" name="location" rows="2" 
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            required></textarea>
                                        <x-input-error class="mt-2" :messages="$errors->get('location')" />
                                    </div>
                                @endif

                                <div>
                                    <x-primary-button>{{ __('Schedule Service') }}</x-primary-button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-8">
                            <h4 class="text-lg font-medium mb-4">{{ __('Service Schedule') }}</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p><strong>Date:</strong> {{ $supportRequest->schedule->scheduled_at->format('F j, Y g:i A') }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($supportRequest->schedule->status) }}</p>
                                @if($supportRequest->schedule->technician)
                                    <p><strong>Technician:</strong> {{ $supportRequest->schedule->technician->user->name }}</p>
                                @endif
                                @if($supportRequest->schedule->location)
                                    <p><strong>Location:</strong> {{ $supportRequest->schedule->location }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Satisfaction Rating Section -->
                    @if($supportRequest->canBeRated())
                        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-8">
                            <h4 class="text-lg font-medium mb-4">{{ __('Rate Our Service') }}</h4>
                            <form method="POST" action="{{ route('satisfaction-ratings.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="support_request_id" value="{{ $supportRequest->id }}">
                                
                                <div>
                                    <x-input-label for="rating" :value="__('Rating')" />
                                    <select id="rating" name="rating" 
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        required>
                                        <option value="">Select a rating</option>
                                        <option value="5">5 - Excellent</option>
                                        <option value="4">4 - Good</option>
                                        <option value="3">3 - Average</option>
                                        <option value="2">2 - Poor</option>
                                        <option value="1">1 - Very Poor</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="feedback" :value="__('Additional Feedback')" />
                                    <textarea id="feedback" name="feedback" rows="3" 
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                                </div>

                                <div>
                                    <x-primary-button>{{ __('Submit Rating') }}</x-primary-button>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if($supportRequest->satisfactionRating)
                        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-8">
                            <h4 class="text-lg font-medium mb-4">{{ __('Customer Rating') }}</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-2xl mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $supportRequest->satisfactionRating->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                    @endfor
                                </p>
                                @if($supportRequest->satisfactionRating->feedback)
                                    <p class="text-gray-600 dark:text-gray-400">{{ $supportRequest->satisfactionRating->feedback }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Comments Section -->
                    <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-8">
                        <h4 class="text-lg font-medium mb-4">{{ __('Comments') }}</h4>
                        
                        <!-- Add Comment Form -->
                        <form method="POST" action="{{ route('comments.store') }}" class="mb-6">
                            @csrf
                            <input type="hidden" name="support_request_id" value="{{ $supportRequest->id }}">
                            
                            <div>
                                <x-input-label for="content" :value="__('Add a comment')" />
                                <textarea id="content" name="content" rows="3" 
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    required></textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            </div>

                            <div class="mt-4">
                                <x-primary-button>{{ __('Add Comment') }}</x-primary-button>
                            </div>
                        </form>

                        <!-- Comments List -->
                        <div class="space-y-6">
                            @foreach($supportRequest->comments as $comment)
                                <div class="flex space-x-3">
                                    <div class="flex-1 bg-gray-50 dark:bg-gray-900 rounded-lg px-4 py-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $comment->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $comment->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <div class="text-gray-700 dark:text-gray-300">
                                            {{ $comment->content }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if(auth()->user()->is_staff)
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <form method="POST" action="{{ route('support-requests.update', $supportRequest) }}" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div>
                                    <x-input-label for="status" :value="__('Update Status')" />
                                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="pending" {{ $supportRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $supportRequest->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $supportRequest->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Update Status') }}</x-primary-button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('support-requests.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600">← Back to Support Requests</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
