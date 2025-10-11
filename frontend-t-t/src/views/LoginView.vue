<template>
  <div class="min-h-screen flex flex-col">
    <!-- Header (same as Landing Page) -->
    <header class="bg-blue-600 text-white p-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold">Task Tracker</h1>
      <nav class="space-x-4">
        <button @click="$router.push({ name: 'Landing' })" class="hover:underline">Home</button>
      </nav>
    </header>

    <!-- Login Form Section -->
    <section class="flex-1 flex items-center justify-center bg-gradient-to-b from-blue-500 to-indigo-700 text-white px-4">
      <div class="bg-white text-gray-800 p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-900">Login to Task Tracker</h2>

        <form @submit.prevent="handleLogin" class="space-y-4">
          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input
              type="email"
              id="email"
              v-model="email"
              required
              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
            />
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input
              type="password"
              id="password"
              v-model="password"
              required
              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
            />
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-all"
          >
            Login
          </button>
        </form>

        <!-- Error Message -->
        <p v-if="error" class="mt-4 text-red-500 text-sm text-center">{{ error }}</p>
      </div>
    </section>

    <!-- Footer (same color scheme as Landing Page) -->
    <footer class="bg-blue-600 text-white text-center py-6">
      <p>© {{ new Date().getFullYear() }} Task Tracker. Contact: support@tasktracker.com</p>
    </footer>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: "LoginPage",
  data() {
    return {
      email: "",
      password: "",
      error: ""
    };
  },
  methods: {
    async handleLogin() {
      this.error = "";
      try {
        const response = await axios.post('http://192.168.10.42:8003/api/login', {
          email: this.email,
          password: this.password
        });

        localStorage.setItem('token', response.data.token);
        this.$router.push({ name: 'Landing' }); // or dashboard page
      } catch (err) {
        this.error = err.response?.data?.message || 'Login failed. Please try again.';
      }
    }
  }
};
</script>
