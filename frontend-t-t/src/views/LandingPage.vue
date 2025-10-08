<template>
  <div class="flex flex-col min-h-screen">
    <!-- Header / Navbar -->
    <header class="bg-blue-600 text-white p-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold">Task Tracker</h1>
      <nav class="space-x-4">
        <button @click="goToLogin" class="hover:underline">Login</button>
        <button @click="$router.push({ name: 'Register' })" class="hover:underline">Register</button>
        <button @click="scrollToFeatures" class="hover:underline">Features</button>
      </nav>
    </header>

    <!-- Hero Section -->
    <section class="flex-1 flex flex-col justify-center items-center text-center bg-gradient-to-b from-blue-500 to-indigo-700 text-white px-4 py-16">
      <h2 class="text-4xl md:text-5xl font-bold mb-4">Manage your tasks efficiently</h2>
      <p class="text-lg md:text-xl mb-6 max-w-xl">
        Track tasks, assign them to team members, and generate reports easily. Boost productivity with a clean and intuitive interface.
      </p>
      <button @click="$router.push({ name: 'Register' })" class="bg-white text-blue-600 font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 transition-all">
        Get Started
      </button>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-gray-100 text-center">
      <h3 class="text-3xl font-bold mb-8">Features</h3>
      <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto px-4">
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-all">
          <h4 class="text-xl font-semibold mb-2">Task Management</h4>
          <p>Create, assign, and track tasks for your team effortlessly.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-all">
          <h4 class="text-xl font-semibold mb-2">Team Collaboration</h4>
          <p>Manage team members, roles, and assignments in one place.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-all">
          <h4 class="text-xl font-semibold mb-2">Reports</h4>
          <p>Generate PDF reports for tasks and monitor productivity.</p>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white text-center py-6">
      <p>© {{ new Date().getFullYear() }} Task Tracker. Contact: support@tasktracker.com</p>
    </footer>
  </div>
</template>

<script>
export default {
  name: "LandingPage",
  methods: {
    goToLogin() {
      this.$router.push({ name: 'Login' });
    },
    scrollToFeatures() {
      const section = document.getElementById("features");
      if (section) section.scrollIntoView({ behavior: "smooth" });
    }
  }
};
</script>

<style scoped>
/* optional custom styles if needed */
</style>
