<template>
  <div class="card">
    <div class="card-body">
      <h2 class="card-title">Page B</h2>
      <a href="/api/download" class="btn btn-primary">Download</a>
    </div>
  </div>
</template>

<script>
export default {
  async created() {
    await fetch('/api/page-b', { method: 'GET' });
  }
}
</script>