<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Support Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('support-requests.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required></textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="service_type" :value="__('Service Type')" />
                                <select id="service_type" name="service_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="Hardware">Hardware</option>
                                    <option value="Software">Software</option>
                                    <option value="Network">Network</option>
                                    <option value="Other">Other</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('service_type')" />
                            </div>

                            <div>
                                <x-input-label for="priority" :value="__('Priority Level')" />
                                <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="low">Low - Not Urgent</option>
                                    <option value="medium">Medium - Normal Priority</option>
                                    <option value="high">High - Important Issue</option>
                                    <option value="urgent">Urgent - Critical Problem</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('priority')" />
                            </div>
                        </div>

                        <div id="location_field" class="hidden">
                            <x-input-label for="location" :value="__('Service Location')" />
                            <textarea id="location" name="location" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('location')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Submit') }}</x-primary-button>
                            <a href="{{ route('support-requests.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">Cancel</a>
                        </div>

                        <script>
                            document.getElementById('service_required').addEventListener('change', function() {
                                const locationField = document.getElementById('location_field');
                                locationField.classList.toggle('hidden', this.value !== 'on_site');
                                document.getElementById('location').required = this.value === 'on_site';
                            });
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
