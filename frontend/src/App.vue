<template>
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
      <div class="container-fluid">
        <router-link class="navbar-brand" to="/">Activity Tracker</router-link>
        <div class="navbar-nav">
          <router-link class="nav-link" to="/page-a">Page A</router-link>
          <router-link class="nav-link" to="/page-b">Page B</router-link>
          <router-link v-if="isAdmin" class="nav-link" to="/stats">Stats</router-link>
          <router-link v-if="isAdmin" class="nav-link" to="/reports">Reports</router-link>
          <button v-if="isLoggedIn" class="btn btn-outline-danger" @click="logout">Logout</button>
          <router-link v-else class="nav-link" to="/login">Login</router-link>
        </div>
      </div>
    </nav>
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user')),
    };
  },
  computed: {
    isLoggedIn() {
      return !!this.user;
    },
    isAdmin() {
      return this.user?.role === 'admin';
    }
  },
  methods: {
    async logout() {
      await fetch('/api/logout', { method: 'POST' });
      localStorage.removeItem('user');
      this.user = null;
      this.$router.push('/login');
    },
    updateUser() {
      this.user = JSON.parse(localStorage.getItem('user'));
    }
  },
  mounted() {
    window.addEventListener('storage', this.updateUser);
    window.addEventListener('user-changed', this.updateUser);
  },
  beforeUnmount() {
    window.removeEventListener('storage', this.updateUser);
    window.removeEventListener('user-changed', this.updateUser);
  }
}
</script>