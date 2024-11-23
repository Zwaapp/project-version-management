<div>
    <div x-data="{ openProjects: {}, highlightedRow: null }" class="relative">
        <!-- Loading Overlay -->
        <div wire:loading class="fixed inset-0 bg-gray-800 flex items-center justify-center z-50 h-12">
            <div class="text-center text-white">
                <div class="flex space-x-2 items-center justify-center h-full">
                    <span class="text-2xl font-semibold">{{ __('Refreshing projects...') }}</span>
                    <i class="fas fa-sync-alt animate-spin text-2xl"></i>
                </div>
            </div>
        </div><!-- Check of er een foutmelding is en toon deze -->
        @if ($error)
            <div class="fixed top-0 left-0 right-0 z-50 flex justify-between items-center bg-red-600 text-white p-4">
                <p class="font-medium">{{ $error }}</p>
                <button wire:click="closeError" class="ml-4 text-white font-semibold hover:text-gray-200">
                    <i class="fas fa-times"></i> {{ __('Close') }}
                </button>
            </div>
        @endif
        @if ($success)
            <div class="fixed top-0 left-0 right-0 z-50 flex justify-between items-center bg-green-600 text-white p-4">
                <p class="font-medium">{{ $success }}</p>
                <button wire:click="closeSuccess" class="ml-4 text-white font-semibold hover:text-gray-200">
                    <i class="fas fa-times"></i> {{ __('Close') }}
                </button>
            </div>
        @endif

        <!-- Projects Table -->
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
            <thead>
            <tr class="border-b border-gray-200">
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Project') }}</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Tags') }}</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Source') }}</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Main branch') }}</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Selected branch') }}</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Packages & Updates') }}</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($projects as $project)
                @php
                    $packagesWithUpdates = $project->packages->filter(fn($package) => $package->latest_version && $package->latest_version !== $package->version)->count();
                    $totalPackages = $project->packages->count();
                @endphp

                <tr
                    x-bind:class="{'bg-blue-50': highlightedRow === '{{ $project->id }}'}"
                    x-on:mouseenter="highlightedRow = '{{ $project->id }}'"
                    x-on:mouseleave="highlightedRow = null"
                    class="border-t border-gray-200 hover:bg-gray-100 transition duration-200">
                    <td class="px-4 py-3 text-sm font-medium text-gray-700">{{ $project->name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">
                        @foreach (explode(',', $project->type) as $type)
                            <span class="text-xs font-semibold text-gray-600 bg-gray-200 p-1 rounded mr-2">{{ $type }}</span>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $project->source }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $project->main_branch }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $project->custom_branch ?? $project->main_branch }}</td>
                    <td class="px-4 py-3 text-sm flex space-x-2">
                        <!-- Refresh Project Icon -->
                        <button wire:click="updatePackages({{ $project->id }})" title="{{ __('Refresh project') }}"
                                class="text-blue-600 hover:text-blue-800 transition">
                            <i class="fas fa-sync-alt"></i>
                        </button>

                        <!-- Update project Icon -->
                        <x-modal title='Edit Project "{{ $project->name }}"' >
                            <livewire:project-update-modal-component :project="$project" wire:key="project-{{ $project->id }}" />
                        </x-modal>

                        <!-- Toggle Packages Icon -->
                        <button x-on:click="openProjects['{{ $project->id }}'] = !openProjects['{{ $project->id }}']"
                                title="{{ __('Toggle packages') }}"
                                class="text-blue-600 hover:text-blue-800 transition">
                            <i :class="openProjects['{{ $project->id }}'] ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                        </button>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <span class="font-medium" :class="{
                            'text-green-600': {{ $packagesWithUpdates }} === 0,
                            'text-yellow-600': {{ $packagesWithUpdates }} > 0 && {{ $packagesWithUpdates }} < {{ $totalPackages }},
                            'text-red-600': {{ $packagesWithUpdates }} === {{ $totalPackages }}
                        }">
                            {{ $packagesWithUpdates }} / {{ $totalPackages }} {{ __('Packages need updates') }}
                        </span>
                    </td>
                </tr>

                <!-- Toggleable package details -->
                <tr x-show="openProjects['{{ $project->id }}']" x-cloak>
                    <td colspan="7" class="bg-gray-50 px-4 py-4">
                        <x-package-list-component :project="$project" />
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
