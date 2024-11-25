<div x-data="{ open: false }" x-cloak>
    <!-- Button to Open Modal -->
    <button @click="open = true"
            title="{{ $title ?? 'Open Modal' }}"
            class="text-blue-600 hover:text-blue-800 transition">
        <i class="{{ $icon ?? 'fas fa-edit' }}"></i>
    </button>

    <!-- Modal Content -->
    <div x-show="open"
        @click.away="open = false"
        @keydown.escape.window="open = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
            <div class="flex justify-between items-center border-b pb-3">
                <h2 class="text-lg font-semibold text-gray-800">{{ $title }}</h2>
                <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>
