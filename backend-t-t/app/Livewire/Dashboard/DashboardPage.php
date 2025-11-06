<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Task;

class DashboardPage extends Component
{
    public $activeTab = 'today';
    public $newTask = '';
    public $tasks = [];

    public function mount()
    {
        $this->loadTasks();
    }

    public function loadTasks()
    {
        if ($this->activeTab === 'today') {
            $this->tasks = Task::whereDate('created_at', now())->get()->toArray();
        } elseif ($this->activeTab === 'all') {
            $this->tasks = Task::all()->toArray();
        } elseif ($this->activeTab === 'team') {
            $this->tasks = []; // Handle team logic separately
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->loadTasks();
    }

    public function handleAddTask()
    {
        $this->validate([
            'newTask' => 'required|string|max:255',
        ]);

        Task::create([
            'text' => $this->newTask,
            'completed' => false,
        ]);

        $this->newTask = '';
        $this->loadTasks();
    }

    public function handleUpdateTask($id)
    {
        $task = Task::find($id);
        if ($task) {
            $task->completed = !$task->completed;
            $task->save();
            $this->loadTasks();
        }
    }

    public function handleDeleteTask($id)
    {
        Task::destroy($id);
        $this->loadTasks();
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-page');
    }
}
