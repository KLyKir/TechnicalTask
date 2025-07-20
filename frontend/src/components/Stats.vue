<template>
  <div class="card">
    <div class="card-body">
      <h2 class="card-title">Statistics</h2>
      <div class="mb-3">
        <label class="form-label">Filter by Date</label>
        <input type="date" v-model="filters.date" class="form-control" @change="fetchStats">
        <label class="form-label">Filter by User</label>
        <input v-model="filters.user" class="form-control" @input="fetchStats">
        <label class="form-label">Filter by Action</label>
        <select v-model="filters.action" class="form-control" @change="fetchStats">
          <option value="">All</option>
          <option value="login">Login</option>
          <option value="logout">Logout</option>
          <option value="registration">Registration</option>
          <option value="view-page">View Page</option>
          <option value="button-click">Button Click</option>
        </select>
      </div>
      <table class="table">
        <thead>
        <tr>
          <th>Username</th>
          <th>Action</th>
          <th>Page</th>
          <th>Date</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="stat in stats" :key="stat.id">
          <td>{{ stat.username }}</td>
          <td>{{ stat.action }}</td>
          <td>{{ stat.page || '-' }}</td>
          <td>{{ stat.created_at }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      stats: [],
      filters: { date: '', user: '', action: '' }
    };
  },
  async created() {
    await this.fetchStats();
  },
  methods: {
    async fetchStats() {
      const response = await fetch('/api/stats', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(this.filters)
      });
      this.stats = await response.json();
    }
  }
}
</script>