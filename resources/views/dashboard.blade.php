@extends('layouts.app')

@section('title', __('Project overview'))

@section('body')
    <div class="min-h-screen bg-gray-100">
        <div class="container mx-auto py-8">
            <h1 class="text-3xl font-bold text-center mb-8">{{ __('Project overview') }}</h1>

            @foreach ($projects as $project)
                <div x-data="{ open: false }" class="bg-white shadow rounded-lg mb-6 p-6 space-y-2">
                    <div>
                        @foreach(explode(',', $project->type) as $type)
                            <span class="text-xs font-semibold text-gray-600 bg-gray-200 p-1 rounded mr-2">{{ $type }}</span>
                        @endforeach
                    </div>
                    <h2
                        x-on:click="open = !open"
                        class="text-2xl font-semibold cursor-pointer text-blue-500 hover:text-blue-700">

                        {{ $project->name }}
                    </h2>
                    <p class="text-gray-600">
                        {{ __('Total nr of packages') }}: {{ $project->packages->count() }}
                    </p>
                    @php
                        $updatesAvailable = $project->packages->filter(fn($package) => $package->latest_version && $package->latest_version !== $package->version)->count();
                    @endphp
                    <p class="text-gray-600">
                        {{ __('Packages with available updates') }}: {{ $updatesAvailable }}
                    </p>

                    <!-- Toggles visibility of the packages list -->
                    <div x-show="open" x-transition class="mt-4 overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">{{ __('Package') }}</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">{{ __('Dependency') }}?</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">{{ __('Framework') }}</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">{{ __('Current') }} version</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">{{ __('Latest') }} version</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">{{ __('Status') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($packages = $project->packages()->orderByDesc('from_composer_json')->orderBy('name')->get())
                            @foreach ($packages as $package)
                                <tr class="border-t border-gray-200">
                                    <td class="px-4 py-2 text-sm font-medium text-gray-700">{{ $package->name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $package->from_composer_json ? __('No') : __('Yes') }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $package->isFramework ? __('Yes') : __('No') }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $package->version }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-600">{{ $package->latest_version ?? __('Unknown') }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        @if ($package->latest_version && $package->latest_version !== $package->version)
                                            <span class="text-red-600 font-semibold">{{ __('Update available') }}</span>
                                        @else
                                            <span class="text-green-600 font-semibold">{{ __('Up to date') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
