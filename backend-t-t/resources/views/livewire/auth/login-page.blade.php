<div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-emerald-600 text-white p-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Task Tracker</h1>
        <nav class="space-x-4">
            <button wire:click="goHome" class="hover:underline">Home</button>
        </nav>
    </header>

    <!-- Login Form Section -->
    <section
        class="flex-1 flex items-center justify-center bg-gradient-to-br from-emerald-500 via-teal-500 to-green-400 text-white px-4">
        <div class="bg-white text-gray-800 p-8 rounded-xl shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-900">Login to Task Tracker</h2>

            <!-- Livewire Login Form -->
            <form wire:submit.prevent="handleLogin" class="space-y-4">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" wire:model="email" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500" />
                    @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" wire:model="password" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500" />
                    @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-emerald-600 text-white py-2 px-4 rounded-md hover:bg-emerald-700 transition-all">
                    Login
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center my-4">
                <hr class="flex-1 border-gray-300" />
                <span class="mx-3 text-gray-500 text-sm">OR</span>
                <hr class="flex-1 border-gray-300" />
            </div>

            <!-- Google Login Button -->
            <div id="googleButton" class="flex justify-center"></div>

            <!-- Error Message -->
            @if($errorMessage)
                <p class="mt-4 text-red-500 text-sm text-center">{{ $errorMessage }}</p>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-emerald-600 text-white text-center py-6">
        <p>© {{ date('Y') }} Task Tracker. Contact: support@tasktracker.com</p>
    </footer>
</div>