<template>
  <div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-blue-600 text-white p-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold">Task Tracker</h1>
      <nav class="space-x-4">
        <button @click="$router.push({ name: 'Landing' })" class="hover:underline">Home</button>
        <button @click="$router.push({ name: 'Login' })" class="hover:underline">Login</button>
      </nav>
    </header>

    <!-- Registration Form Section -->
    <section
      class="flex-1 flex items-center justify-center bg-gradient-to-b from-blue-500 to-indigo-700 text-white px-4"
    >
      <div class="bg-white text-gray-800 p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-900">
          Create Your Account
        </h2>

        <!-- Form -->
        <form @submit.prevent="handleRegister" class="space-y-4">
          <!-- Name -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
              type="text"
              id="name"
              v-model="name"
              required
              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
            />
          </div>

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

          <!-- Confirm Password -->
          <div>
            <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input
              type="password"
              id="confirmPassword"
              v-model="confirmPassword"
              required
              class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
            />
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-all"
          >
            Register
          </button>
        </form>

        <!-- Divider -->
        <div class="flex items-center my-4">
          <hr class="flex-1 border-gray-300" />
          <span class="mx-3 text-gray-500 text-sm">OR</span>
          <hr class="flex-1 border-gray-300" />
        </div>

        <!-- Google Button -->
        <div id="googleButton" class="flex justify-center"></div>

        <!-- Error Message -->
        <p v-if="error" class="mt-4 text-red-500 text-sm text-center">{{ error }}</p>
        <p v-if="success" class="mt-4 text-green-500 text-sm text-center">{{ success }}</p>
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
const GOOGLE_CLIENT_ID = import.meta.env.VITE_GOOGLE_CLIENT_ID;
export default {
  name: "RegisterView",
  data() {
    return {
      name: "",
      email: "",
      password: "",
      confirmPassword: "",
      error: "",
      success: "",
    };
  },
  
  mounted() {
    // Load Google Identity Services
    const script = document.createElement("script");
    script.src = "https://accounts.google.com/gsi/client";
    script.async = true;
    script.defer = true;
    script.onload = this.initializeGoogleSignIn;
    document.head.appendChild(script);
  },
  methods: {
    async handleRegister() {
      this.error = "";
      this.success = "";

      // Validation
      if (!this.name || !this.email || !this.password || !this.confirmPassword) {
        this.error = "All fields are required.";
        return;
      }

      if (this.password.length < 8) {
        this.error = "Password must be at least 8 characters.";
        return;
      }

      if (this.password !== this.confirmPassword) {
        this.error = "Passwords do not match.";
        return;
      }

      try {
        const response = await axios.post("http://192.168.10.42:8003/api/register", {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.confirmPassword,
        });

        this.success = "Registration successful! Redirecting to login...";
        setTimeout(() => {
          this.$router.push({ name: "Login" });
        }, 2000);
      } catch (err) {
        this.error = err.response?.data?.message || "Registration failed. Please try again.";
      }
    },

    // Initialize Google login button
    initializeGoogleSignIn() {
      console.log(this.GOOGLE_CLIENT_ID)
      window.google.accounts.id.initialize({
        client_id: GOOGLE_CLIENT_ID,
        callback: this.handleGoogleResponse,
      });

      window.google.accounts.id.renderButton(document.getElementById("googleButton"), {
        theme: "outline",
        size: "large",
        width: 300,
      });
    },

    async handleGoogleResponse(response) {
      try {
        const res = await axios.post("http://192.168.10.42:8003/api/google/login", {
          credential: response.credential,
        });

        localStorage.setItem("auth_token", res.data.token);
        this.success = "Google sign-up successful! Redirecting...";
        setTimeout(() => {
          this.$router.push({ name: "Dashboard" });
        }, 1500);
      } catch (error) {
        this.error = "Google sign-in failed. Please try again.";
        console.error("Google login error:", error);
      }
    },
  },
};
</script>

<style scoped>
button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
