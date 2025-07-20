<template>
  <div class="card">
    <div class="card-body">
      <h2 class="card-title">Reports</h2>

      <div class="mb-3">
        <label class="form-label">Start Date</label>
        <input type="date" v-model="startDate" class="form-control" @change="fetchReports">

        <label class="form-label">End Date</label>
        <input type="date" v-model="endDate" class="form-control" @change="fetchReports">
      </div>

      <button class="btn btn-primary mb-3" @click="downloadCsv">
        Download CSV
      </button>

      <canvas id="reportChart"></canvas>

      <table class="table mt-4">
        <thead>
        <tr>
          <th>Date</th>
          <th>Page A Views</th>
          <th>Page B Views</th>
          <th>Buy Cow Clicks</th>
          <th>Download Clicks</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="report in reports" :key="report.date">
          <td>{{ report.date }}</td>
          <td>{{ report.pageAViews }}</td>
          <td>{{ report.pageBViews }}</td>
          <td>{{ report.buyCowClicks }}</td>
          <td>{{ report.downloadClicks }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import Chart from 'chart.js/auto';

export default {
  data() {
    return {
      reports: [],
      startDate: '',
      endDate: '',
      chart: null
    };
  },
  async created() {
    const now = new Date();
    const thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(now.getDate() - 30);

    this.startDate = thirtyDaysAgo.toISOString().split('T')[0];
    this.endDate = now.toISOString().split('T')[0];

    await this.fetchReports();
  },
  methods: {
    async fetchReports() {
      try {
        const response = await fetch('/api/reports', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            startDate: this.startDate,
            endDate: this.endDate
          })
        });

        const json = await response.json();

        if (!response.ok) {
          alert(json.error || 'Failed to fetch reports');
          return;
        }

        this.reports = json.reports || [];
        this.updateChart();
      } catch (err) {
        alert('An error occurred while fetching reports.');
        console.error(err);
      }
    },

    downloadCsv() {
      const params = new URLSearchParams({
        startDate: this.startDate,
        endDate: this.endDate
      });

      window.location.href = `/api/reports/download?${params.toString()}`;
    },

    updateChart() {
      const ctx = document.getElementById('reportChart').getContext('2d');
      if (this.chart) {
        this.chart.destroy();
      }

      this.chart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: this.reports.map(r => r.date),
          datasets: [
            {
              label: 'Page A Views',
              data: this.reports.map(r => r.pageAViews),
              borderColor: 'blue',
              fill: false
            },
            {
              label: 'Page B Views',
              data: this.reports.map(r => r.pageBViews),
              borderColor: 'green',
              fill: false
            },
            {
              label: 'Buy Cow Clicks',
              data: this.reports.map(r => r.buyCowClicks),
              borderColor: 'red',
              fill: false
            },
            {
              label: 'Download Clicks',
              data: this.reports.map(r => r.downloadClicks),
              borderColor: 'purple',
              fill: false
            }
          ]
        },
        options: {
          responsive: true,
          scales: {
            x: {
              title: {
                display: true,
                text: 'Date'
              }
            },
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Count'
              }
            }
          }
        }
      });
    }
  }
};
</script>

<style scoped>
.card {
  max-width: 900px;
  margin: 0 auto;
}
</style>