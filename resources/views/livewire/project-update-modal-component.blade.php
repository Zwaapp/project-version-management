<div>
    <div class="mt-4">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">{{ __('Main Branch') }}</label>
            <input type="text" wire:model="branch" value="{{ $project->main_branch }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg"/>
        </div>
    </div>
    <div class="mt-6 flex justify-end">
        <button wire:click="update()" @click="open = false" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-check"></i> {{ __('Save') }}
        </button>
    </div>
</div>
