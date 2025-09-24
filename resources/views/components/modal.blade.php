@props(['title' => 'Default Title']) <!-- Default value jika $title tidak diberikan -->

<div x-data="{ showModal: false }" x-show="showModal"
    class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50" x-cloak>
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-2">
            <h2 class="text-lg font-bold">{{ $title }}</h2>
            <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
                &times;
            </button>
        </div>

        <!-- Content -->
        <div class="mt-4">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="mt-4 flex justify-end">
            <button class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 mr-2" @click="showModal = false">
                Cancel
            </button>
            <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Confirm
            </button>
        </div>
    </div>
</div>
