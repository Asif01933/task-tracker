<div class="flex flex-col min-h-screen bg-gradient-to-b from-emerald-50 via-white to-teal-50 text-gray-800">

    <!-- Header / Navbar -->
    <header class="bg-white shadow-sm border-b border-gray-100 p-6 flex justify-between items-center sticky top-0 z-10">
        <h1 class="text-2xl font-bold text-emerald-600">Task Tracker</h1>
        <nav class="space-x-6 text-gray-700 font-medium">
            <button wire:click="goToLogin" class="hover:text-emerald-600 transition-colors">Login</button>
            <button wire:click="goToRegister" class="hover:text-emerald-600 transition-colors">Register</button>
            <button wire:click="scrollToFeatures" class="hover:text-emerald-600 transition-colors">Features</button>
        </nav>
    </header>

    <!-- Hero Section -->
    <section
        class="flex-1 flex flex-col justify-center items-center text-center bg-gradient-to-br from-emerald-500 via-teal-500 to-green-400 text-white px-4 py-20 relative overflow-hidden">
        <!-- Optional pattern layer -->
        <div
            class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/polished-metal.png')]">
        </div>

        <div class="relative z-10 max-w-3xl">
            <h2 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight drop-shadow">
                Focus. Organize. Grow.
            </h2>
            <p class="text-lg md:text-xl mb-8 text-emerald-100">
                A smarter way to manage tasks, collaborate with your team, and achieve goals beautifully.
            </p>
            <button wire:click="goToRegister"
                class="bg-white text-emerald-600 font-semibold px-8 py-3 rounded-xl shadow-md hover:bg-emerald-50 hover:shadow-lg transition-all duration-300">
                Get Started
            </button>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h3 class="text-4xl font-bold text-gray-800 mb-12">Key Features</h3>
            <div class="grid md:grid-cols-3 gap-10">
                <div
                    class="bg-emerald-50 p-8 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                    <h4 class="text-xl font-semibold mb-2 text-emerald-700">Task Management</h4>
                    <p class="text-gray-600">Easily create, assign, and monitor tasks across teams.</p>
                </div>
                <div
                    class="bg-emerald-50 p-8 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                    <h4 class="text-xl font-semibold mb-2 text-emerald-700">Collaboration</h4>
                    <p class="text-gray-600">Coordinate with your team seamlessly in one platform.</p>
                </div>
                <div
                    class="bg-emerald-50 p-8 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                    <h4 class="text-xl font-semibold mb-2 text-emerald-700">Reports</h4>
                    <p class="text-gray-600">Visualize productivity and project progress effortlessly.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-emerald-600 text-white text-center py-8">
        <p>© {{ date('Y') }} Task Tracker. Built with ❤️ for better productivity.</p>
    </footer>
</div>