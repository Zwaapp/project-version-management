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
                    @if (!$package->latest_version)
                        <span class="text-yellow-600 font-semibold">{{ __('Unknown') }}</span>
                    @elseif (!$package->hasLatestVersion())
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
