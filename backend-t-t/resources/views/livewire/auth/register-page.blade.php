<div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-emerald-600 text-white p-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Task Tracker</h1>
        <nav class="space-x-4">
            <button wire:click="goHome" class="hover:underline">Home</button>
            <a href="{{ route('login') }}" class="hover:underline">Login</a>
        </nav>
    </header>

    <!-- Registration Form -->
    <section
        class="flex-1 flex items-center justify-center bg-gradient-to-b from-emerald-500 to-teal-600 text-white px-4">
        <div class="bg-white text-gray-800 p-8 rounded-xl shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-900">Create Your Account</h2>

            <form wire:submit.prevent="handleRegister" class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" wire:model="name"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500" />
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" wire:model="email"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500" />
                    @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" wire:model="password"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500" />
                    @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" wire:model="confirmPassword"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500" />
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-emerald-600 text-white py-2 px-4 rounded-md hover:bg-emerald-700 transition-all">
                    Register
                </button>
            </form>

            <!-- Error & Success Messages -->
            @if ($error)
                <p class="mt-4 text-red-500 text-sm text-center">{{ $error }}</p>
            @endif

            @if ($success)
                <p class="mt-4 text-green-600 text-sm text-center">{{ $success }}</p>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-emerald-600 text-white text-center py-6">
        <p>© {{ date('Y') }} Task Tracker. Contact: support@tasktracker.com</p>
    </footer>
</div>