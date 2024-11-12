<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Farmer Dashboard</h2>
    </template>

    <div class="flex">
      <!-- Sidebar -->

      <!-- Main Content -->
      <main class="flex-grow p-4">

        <!-- Analytics Section -->
        <div class="mt-6">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Analytics</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
              <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">Total Pigs</h4>
              <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ analytics.totalPigs }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
              <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">Average Weight</h4>
              <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ analytics.averageWeight.toFixed(2) }} kg</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
              <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">Weight Trend</h4>
              <canvas id="weightTrendChart"></canvas>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
              <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">Current Weather</h4>
              <p class="text-lg text-gray-800 dark:text-gray-200">Temperature: {{ weather.temperature }} °C</p>
              <p class="text-lg text-gray-800 dark:text-gray-200">Status: {{ weather.weather }}</p>
            </div>
          </div>
        </div>
      </main>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Chart from 'chart.js/auto';
import zoomPlugin from 'chartjs-plugin-zoom';

Chart.register(zoomPlugin);

const analytics = ref({
  totalPigs: 0,
  averageWeight: 0,
  weightTrend: [],
});

const weather = ref({
  temperature: 0,
  weather: '',
});

const fetchAnalytics = async () => {
  try {
    const response = await fetch('/api/analytics', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
      },
    });
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    console.log('Analytics data:', data); // Log the response data
    analytics.value = data;
    renderWeightTrendChart(data.weightTrend);
  } catch (error) {
    console.error('Error fetching analytics:', error);
  }
};

const fetchWeather = async () => {
  try {
    const response = await fetch('/api/weather', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
      },
    });
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    weather.value = data;
  } catch (error) {
    console.error('Error fetching weather:', error);
  }
};

const renderWeightTrendChart = (weightTrend) => {
  const ctx = document.getElementById('weightTrendChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: weightTrend.map(item => item.date),
      datasets: [{
        label: 'Average Weight (kg)',
        data: weightTrend.map(item => item.average_weight),
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        fill: true,
      }],
    },
    options: {
      responsive: true,
      plugins: {
        zoom: {
          pan: {
            enabled: true,
            mode: 'xy',
          },
          zoom: {
            enabled: true,
            mode: 'xy',
          },
        },
      },
      scales: {
        x: {
          type: 'time',
          time: {
            unit: 'day',
          },
        },
        y: {
          beginAtZero: true,
        },
      },
    },
  });
};

onMounted(() => {
  fetchAnalytics();
  fetchWeather();
});
</script>

<style>
/* Add any necessary styles here */
</style>