<x-modal title='Edit Project "{{ $project->name }}"' >
    <div x-data="{ branch: '{{ $project->custom_branch ?? $project->main_branch }}' }">
        <div class="mt-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">{{ __('Branch to check packages') }}</label>
                <input
                    x-model="branch"
                    type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button
                @click="$dispatch('updatedCustomBranch', { branch: branch, projectId: {{ $project->id }} }); open=false"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
            >
                <i class="fas fa-check"></i> {{ __('Save') }}
            </button>
        </div>
    </div>
</x-modal>
