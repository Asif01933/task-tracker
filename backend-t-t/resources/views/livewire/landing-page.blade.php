<div
    class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-r from-indigo-500 to-blue-600 text-white">
    <h1 class="text-4xl font-bold mb-6">Welcome to My Livewire Landing Page 🚀</h1>

    <div class="bg-white text-gray-900 p-6 rounded-lg shadow-lg w-80 text-center">
        <h2 class="text-lg font-semibold mb-4">Say Hello!</h2>
        <input type="text" wire:model="name" placeholder="Enter your name"
            class="border rounded w-full p-2 mb-4 focus:ring-2 focus:ring-indigo-400 focus:outline-none" />

        @if($name)
            <p class="text-gray-700">👋 Hello, <span class="font-bold text-indigo-600">{{ $name }}</span>!</p>
        @else
            <p class="text-gray-500">Type your name above to get a greeting hhiiii.</p>
        @endif
    </div>

    <p class="mt-8 text-sm text-gray-100">Powered by Laravel + Livewire + TailwindCSS</p>
</div>