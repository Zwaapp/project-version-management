<div class="bg-white border border-gray-200 rounded-lg shadow-sm">
    <h1 class="p-8 text-2xl">{{ count($projects) }} {{ __('projects found') }}</h1>
    <!-- Header -->
    <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-600">
        <div>{{ __('Project') }}</div>
        <div>{{ __('Tags') }}</div>
        <div>{{ __('Source') }}</div>
        <div>{{ __('Main branch') }}</div>
        <div>{{ __('Selected branch') }}</div>
        <div>{{ __('Packages & Updates') }}</div>
        <div>{{ __('Actions') }}</div>
    </div>

    <!-- Project Rows -->
    @foreach ($projects as $project)
        <div x-data="{ isOpen: false }" class="border-t border-gray-200">
            <!-- Main Row -->
            <div
                class="grid grid-cols-7 items-center px-4 py-3 hover:bg-gray-100 transition"
                x-bind:class="{ 'bg-blue-50': isOpen }"
            >
                <div class="text-sm font-medium text-gray-700 pr-2">{{ $project->name }}</div>
                <div class="text-sm text-gray-600">
                    @if(trim($project->type))
                        @foreach (explode(',', $project->type) as $type)
                            <span class="text-xs font-semibold text-gray-600 bg-gray-200 p-1 rounded mr-2">{{ $type }}</span>
                        @endforeach
                    @endif
                </div>
                <div class="text-sm text-gray-600">{{ $project->source }}</div>
                <div class="text-sm text-gray-600">{{ $project->main_branch }}</div>
                <div class="text-sm text-gray-600">{{ $project->custom_branch ?? $project->main_branch }}</div>
                <div class="text-sm flex space-x-2">
                    <!-- Refresh Project Icon -->
                    <button wire:click="updatePackages({{ $project->id }})" title="{{ __('Refresh project') }}"
                            class="text-blue-600 hover:text-blue-800 transition">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <!-- Update project Icon -->
                    <x-edit-project-component :project="$project" />
                    <!-- Trash project Icon -->
                    <button wire:click="removeProject({{ $project->id }})" title="{{ __('Remove project') }}"
                            class="text-blue-600 hover:text-blue-800 transition">
                        <i class="fas fa-trash"></i>
                    </button>
                    <!-- Toggle Packages Icon -->
                    <button x-on:click="isOpen = !isOpen"
                            title="{{ __('Toggle packages') }}"
                            class="text-blue-600 hover:text-blue-800 transition">
                        <i :class="isOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                    </button>
                </div>
                <div class="text-sm">
                    <span class="font-medium" :class="{
                        'text-green-600': {{ $project->packages->filter(fn($package) => !$package->hasLatestVersion())->count() }} === 0,
                        'text-yellow-600': {{ $project->packages->filter(fn($package) => !$package->hasLatestVersion())->count() }} > 0 && {{ $project->packages->count() }},
                        'text-red-600': {{ $project->packages->filter(fn($package) => !$package->hasLatestVersion())->count() }} === {{ $project->packages->count() }}
                    }">
                        {{ $project->packages->filter(fn($package) => !$package->hasLatestVersion())->count() }} / {{ $project->packages->count() }} {{ __('Packages need updates') }}
                    </span>
                </div>
            </div>

            <!-- Package Details Row -->
            <div x-show="isOpen" x-cloak class="bg-gray-50 px-4 py-4">
                <x-package-list-component :project="$project" />
            </div>
        </div>
    @endforeach
</div>
