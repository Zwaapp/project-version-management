@extends('layouts.app')

@section('title', __('Project overview'))

@section('body')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto py-6">
            <h1 class="text-3xl font-semibold text-center text-gray-800 mb-6">{{ __('Project overview') }}</h1>

            <!-- Zoekveld -->
            <div class="mb-4 flex justify-center">
                <form method="GET" action="{{ route('home') }}" class="flex items-center bg-white p-3 rounded-lg shadow-sm w-1/2 sm:w-2/3">
                    <input
                        type="text"
                        name="search"
                        value="{{ request()->search }}"
                        placeholder="{{ __('Search by project name or type...') }}"
                        class="p-2 border border-gray-300 rounded-l-lg w-full focus:outline-none focus:ring-1 focus:ring-blue-400 text-sm"
                    />
                    <button type="submit" class="ml-3 p-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition duration-200">
                        <i class="fas fa-search text-sm"></i> {{ __('Search') }}
                    </button>
                </form>
            </div>

            <!-- Projectenlijst -->
            <div x-data="{ openProjects: {}, highlightedRow: null }">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                    <tr class="border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Project') }}</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Type') }}</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Source') }}</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Branch') }}</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Packages & Updates') }}</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($projects as $project)
                        @php
                            // Aantal packages dat een update nodig heeft
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
                            <td class="px-4 py-3 text-sm">
                                <span class="font-medium" :class="{
                                    'text-green-600': {{ $packagesWithUpdates }} === 0,
                                    'text-yellow-600': {{ $packagesWithUpdates }} > 0 && {{ $packagesWithUpdates }} < {{ $totalPackages }},
                                    'text-red-600': {{ $packagesWithUpdates }} === {{ $totalPackages }}
                                }">
                                    {{ $packagesWithUpdates }} / {{ $totalPackages }} {{ __('Packages need updates') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <button
                                    x-on:click="openProjects['{{ $project->id }}'] = !openProjects['{{ $project->id }}']"
                                    class="px-3 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition duration-200">
                                    <i class="fas fa-cogs text-xs"></i> {{ __('Toggle Packages') }}
                                </button>
                            </td>
                        </tr>

                        <!-- Toggleable package details -->
                        <tr x-show="openProjects['{{ $project->id }}']" x-cloak>
                            <td colspan="7" class="bg-gray-50 px-4 py-4">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                                        <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Package') }}</th>
                                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Dependency') }}?</th>
                                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Framework') }}</th>
                                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Current') }} version</th>
                                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Latest') }} version</th>
                                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">{{ __('Status') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php($packages = $project->packages()->orderByDesc('from_composer_json')->orderBy('name')->get())
                                        @foreach ($packages as $package)
                                            <tr class="border-t border-gray-200 @if(!$package->from_composer_json) opacity-50 @endif">
                                                <td class="px-4 py-3 text-sm font-medium text-gray-700">{{ $package->name }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $package->from_composer_json ? __('No') : __('Yes') }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $package->isFramework ? __('Yes') : __('No') }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $package->version }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-600">{{ $package->latest_version ?? __('Unknown') }}</td>
                                                <td class="px-4 py-3 text-sm">
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
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
