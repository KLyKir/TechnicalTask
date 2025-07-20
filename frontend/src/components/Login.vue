<template>
  <div class="card">
    <div class="card-body">
      <h2 class="card-title">Login</h2>
      <form @submit.prevent="login">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input v-model="username" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input v-model="password" type="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <router-link to="/register" class="btn btn-link">Register</router-link>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: '',
      password: ''
    };
  },
  methods: {
    async login() {
      try {
        const response = await fetch('/api/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username: this.username, password: this.password })
        });
        const data = await response.json();
        if (data.success) {
          localStorage.setItem('user', JSON.stringify({ role: data.role }));
          window.dispatchEvent(new Event('user-changed'));
          this.$router.push('/page-a');
        } else {
          alert(data.error);
        }
      } catch (error) {
        alert('Login failed');
      }
    }
  }
}
</script>