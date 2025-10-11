<template>
  <div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-blue-600 text-white p-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold">Task Tracker</h1>
      <nav class="space-x-4">
        <button @click="$router.push({ name: 'Dashboard' })" class="hover:underline">Dashboard</button>
        <button @click="logout" class="hover:underline">Logout</button>
      </nav>
    </header>

    <!-- Task Section -->
    <section class="flex-1 p-6 bg-gray-100">
      <!-- Add Task -->
      <div class="max-w-md mx-auto mb-6">
        <h2 class="text-xl font-bold mb-4">Add New Task</h2>
        <form @submit.prevent="addTask" class="flex space-x-2">
          <input
            v-model="newTask"
            type="text"
            placeholder="Enter task title"
            class="flex-1 px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500"
          />
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
          >
            Add
          </button>
        </form>
        <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
      </div>

      <!-- Task List -->
      <div class="max-w-md mx-auto">
        <h2 class="text-xl font-bold mb-4">Your Tasks</h2>
        <ul>
          <li
            v-for="task in tasks"
            :key="task.id"
            class="flex justify-between items-center bg-white p-3 mb-2 rounded-md shadow"
          >
            <div class="flex items-center space-x-2">
              <input
                type="checkbox"
                :checked="task.completed"
                @change="toggleComplete(task)"
              />
              <span :class="{ 'line-through text-gray-400': task.completed }">
                {{ task.title }}
              </span>
            </div>
            <button
              @click="deleteTask(task.id)"
              class="text-red-500 hover:text-red-700"
            >
              Delete
            </button>
          </li>
        </ul>
        <p v-if="tasks.length === 0" class="text-gray-500 text-center mt-4">
          No tasks yet. Add one above!
        </p>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white text-center py-6">
      <p>© {{ new Date().getFullYear() }} Task Tracker. Contact: support@tasktracker.com</p>
    </footer>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "TasksView",
  data() {
    return {
      newTask: "",
      tasks: [],
      error: "",
    };
  },
  mounted() {
    this.fetchTasks();
  },
  methods: {
    getAuthHeaders() {
      const token = localStorage.getItem("auth_token");
      return { Authorization: `Bearer ${token}` };
    },

    async fetchTasks() {
      try {
        const res = await axios.get(
          "http://192.168.10.42:8003/api/tasks",
          { headers: this.getAuthHeaders() }
        );
        this.tasks = res.data;
      } catch (err) {
        this.error = "Failed to fetch tasks";
      }
    },

    async addTask() {
      if (!this.newTask.trim()) {
        this.error = "Task title cannot be empty.";
        return;
      }
      this.error = "";

      try {
        const res = await axios.post(
          "http://192.168.10.42:8003/api/tasks",
          { title: this.newTask },
          { headers: this.getAuthHeaders() }
        );
        this.tasks.push(res.data);
        this.newTask = "";
      } catch (err) {
        this.error = "Failed to add task.";
      }
    },

    async toggleComplete(task) {
      try {
        const res = await axios.patch(
          `http://192.168.10.42:8003/api/tasks/${task.id}`,
          { completed: !task.completed },
          { headers: this.getAuthHeaders() }
        );
        task.completed = res.data.completed;
      } catch (err) {
        this.error = "Failed to update task.";
      }
    },

    async deleteTask(taskId) {
      try {
        await axios.delete(
          `http://192.168.10.42:8003/api/tasks/${taskId}`,
          { headers: this.getAuthHeaders() }
        );
        this.tasks = this.tasks.filter((t) => t.id !== taskId);
      } catch (err) {
        this.error = "Failed to delete task.";
      }
    },

    logout() {
      localStorage.removeItem("auth_token");
      this.$router.push({ name: "Login" });
    },
  },
};
</script>
