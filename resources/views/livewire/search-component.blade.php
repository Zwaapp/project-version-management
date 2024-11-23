<div class="mb-4 flex justify-center">
    <div class="flex items-center bg-white p-3 rounded-lg shadow-sm w-1/2 sm:w-2/3">
        <input
            wire:model.live="search"
            type="text"
            name="search"
            placeholder="{{ __('Search by project name or type...') }}"
            class="p-2 border border-gray-300 rounded-l-lg w-full focus:outline-none focus:ring-1 focus:ring-blue-400 text-sm"
        />
        <button type="submit" class=" p-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition duration-200">
            <i class="fas fa-search text-sm"></i>
        </button>
    </div>
</div>
