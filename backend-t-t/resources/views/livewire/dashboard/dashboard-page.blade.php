<div class="min-h-screen flex bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md p-6 flex flex-col">
        <h2 class="text-xl font-bold mb-6">Dashboard</h2>
        <nav class="flex flex-col space-y-2">
            <button wire:click="switchTab('today')"
                class="text-left px-3 py-2 rounded hover:bg-gray-200 {{ $activeTab === 'today' ? 'bg-gray-200 font-semibold' : '' }}">
                Today's Tasks
            </button>
            <button wire:click="switchTab('all')"
                class="text-left px-3 py-2 rounded hover:bg-gray-200 {{ $activeTab === 'all' ? 'bg-gray-200 font-semibold' : '' }}">
                All Tasks
            </button>
            <button wire:click="switchTab('team')"
                class="text-left px-3 py-2 rounded hover:bg-gray-200 {{ $activeTab === 'team' ? 'bg-gray-200 font-semibold' : '' }}">
                Team
            </button>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        @if($activeTab === 'today')
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">Today's Tasks</h2>

            <!-- Add Task Form -->
            <div class="bg-white p-4 rounded-lg shadow-md mb-6">
                <form wire:submit.prevent="handleAddTask" class="flex flex-col sm:flex-row gap-3">
                    <input type="text" wire:model="newTask" placeholder="Add a new task..."
                        class="flex-grow p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                    <button type="submit"
                        class="bg-emerald-600 text-white font-semibold py-3 px-6 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-200">
                        Add Task
                    </button>
                </form>
            </div>

            <!-- Task List -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <h2>Todays Tasks</h2>
                <ul class="divide-y divide-gray-200">
                    @foreach($tasks as $task)
                        <li class="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors duration-150">
                            <span class="{{ $task['completed'] ? 'line-through text-gray-400' : 'text-gray-700' }}">
                                {{ $task['text'] }}
                            </span>
                            <div class="flex items-center gap-2 sm:gap-4">
                                <button wire:click="handleUpdateTask({{ $task['id'] }})"
                                    class="text-blue-500 hover:text-blue-700 transition-colors duration-200"
                                    aria-label="Update task">
                                    ✏️
                                </button>
                                <button wire:click="handleDeleteTask({{ $task['id'] }})"
                                    class="text-red-500 hover:text-red-700 transition-colors duration-200"
                                    aria-label="Delete task">
                                    🗑️
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @elseif($activeTab === 'all')
            <h2 class="text-2xl font-bold text-gray-800 mb-6">All Tasks</h2>
            <p>List all tasks here...</p>
        @elseif($activeTab === 'team')
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Team</h2>
            <p>Team members information here...</p>
        @endif
    </main>
</div>