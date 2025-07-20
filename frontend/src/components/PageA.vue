<template>
  <div class="card">
    <div class="card-body">
      <h2 class="card-title">Page A</h2>
      <button v-if="!bought" class="btn btn-primary" @click="buyCow">Buy a Cow</button>
      <p v-if="bought" class="alert alert-success">Thank You!</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return { bought: false };
  },
  async created() {
    await fetch('/api/page-a', { method: 'GET' });
  },
  methods: {
    async buyCow() {
      await fetch('/api/page-a', { method: 'POST' });
      this.bought = true;
    }
  }
}
</script>