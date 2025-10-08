<template>
  <div class="landing-page">
    <header class="header">
      <h1>Task Tracker</h1>
      <nav>
        <a href="#features">Features</a>
        <a href="#login">Login</a>
      </nav>
    </header>

    <section class="hero">
      <h2>Manage your tasks efficiently</h2>
      <p>Track tasks, assign to team members, and generate reports easily.</p>
      <button @click="goToLogin">Get Started</button>
    </section>

    <section id="features">
      <h3>Features</h3>
      <ul>
        <li>Create and assign tasks</li>
        <li>Team management</li>
        <li>Generate PDF reports</li>
      </ul>
    </section>

    <footer>
      <p>Contact: support@tasktracker.com</p>
    </footer>
  </div>
</template>

<script>
export default {
  name: "LandingPage",
  methods: {
    goToLogin() {
      alert("Redirect to login page (Vue Router will handle this later)");
    }
  }
};
</script>

<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
}
.header {
  display: flex;
  justify-content: space-between;
  padding: 20px;
  background: #333;
  color: #fff;
}
.header a {
  color: #fff;
  margin-left: 15px;
  text-decoration: none;
}
.hero {
  text-align: center;
  margin-top: 100px;
}
.hero button {
  margin-top: 20px;
  padding: 10px 20px;
  font-size: 16px;
}
section {
  padding: 50px;
  text-align: center;
}
footer {
  background: #333;
  color: #fff;
  padding: 20px;
}
</style>
