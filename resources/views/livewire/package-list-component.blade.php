<div class="bg-white border border-gray-200 rounded-lg shadow-sm">
    <h1 class="p-8 text-2xl">
        {{ count($packages) }} {{ __('packages found for search term') }}
    </h1>

    <!-- Header -->
    <div class="grid grid-cols-6 border-b border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-600">
        <div>{{ __('Project Name') }}</div>
        <div>{{ __('Current branch') }}</div>
        <div>{{ __('Package Name') }}</div>
        <div>{{ __('Current Version') }}</div>
        <div>{{ __('Latest Version') }}</div>
        <div>{{ __('Can update') }}</div>
    </div>

    @if(!count($packages))
        <div class="p-8 text-center text-gray-600">
            {{ __('No packages found. Start searching first') }}
        </div>
    @endif
    <!-- Package Rows -->
    @foreach ($packages as $package)
        <div class="grid grid-cols-6 items-center px-4 py-3 border-t border-gray-200 hover:bg-gray-100 transition">
            <!-- Project Name -->
            <div class="text-sm font-medium text-gray-700">
                {{ $package->project->name }}
            </div>
            <div class="text-sm font-medium text-gray-700">
                {{ $package->project->custom_branch ?? $package->project->main_branch }}
            </div>

            <!-- Package Name -->
            <div class="text-sm text-gray-600">
                {{ $package->name }}
            </div>

            <!-- Current Version -->
            <div class="text-sm text-gray-600">
                {{ $package->version }}
            </div>

            <!-- Latest Version -->
            <div class="text-sm text-gray-600">
                @if ($package->latest_version)
                    {{ $package->latest_version }}
                @else
                    <span class="text-red-600">{{ __('Unknown') }}</span>
                @endif
            </div>
            <div>
                @if(!$package->latest_version)
                    <span class="text-yellow-600">{{ __('Unknown') }}</span>
                @elseif ($package->hasLatestVersion())
                    <span class="text-green-600">{{ __('Yes') }}</span>
                @else
                    <span class="text-red-600">{{ __('No') }}</span>
                @endif
            </div>
        </div>
    @endforeach
</div>
