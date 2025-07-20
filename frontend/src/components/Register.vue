<template>
  <div class="card">
    <div class="card-body">
      <h2 class="card-title">Register</h2>
      <form @submit.prevent="register">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input v-model="username" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input v-model="password" type="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
        <router-link to="/login" class="btn btn-link">Login</router-link>
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
    async register() {
      try {
        const response = await fetch('/api/register', {
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
        alert('Registration failed');
      }
    }
  }
}
</script>