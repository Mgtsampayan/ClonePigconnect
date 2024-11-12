<template>
  <div>
    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Buyer Preferences</h2>
      </template>
      <main>
        <div class="flex justify-center">
          <div class="w-[75%]">
            <form @submit.prevent="submitForm" class="place-content-center">
              <div class="mb-4">
                <label for="preferredWeight" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Preferred Weight</label>
                <input type="text" id="preferredWeight" v-model="preferredWeight" class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" />
              </div>

              <div class="mb-4">
                <label for="ageOfPigs" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Age of Pigs (in months)</label>
                <select id="ageOfPigs" v-model="ageOfPigs" class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="Older">Older</option>
                </select>
              </div>

              <div class="mb-4">
                <label for="priceRange" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price Range</label>
                <input type="text" id="priceRange" v-model="priceRange" class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" />
              </div>

              <button type="submit" class="w-full bg-blue-500 text-white p-2.5 rounded-lg focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:focus:ring-blue-800">Submit</button>
            </form>
          </div>
        </div>
      </main>
    </AuthenticatedLayout>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

const preferredWeight = ref('');
const ageOfPigs = ref('');
const priceRange = ref('');
const userId = ref(null);

const fetchUserId = async () => {
  try {
    const response = await axios.get('/api/user', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    });
    userId.value = response.data.id;
  } catch (error) {
    console.error('Error fetching user ID:', error);
  }
};

const fetchBuyerPreferences = async () => {
  try {
    const response = await axios.get('/api/buyers_preference', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    });
    const data = response.data;
    console.log(data);

    if (data) {
      preferredWeight.value = data.preferred_weight;
      ageOfPigs.value = data.age_of_pigs;
      priceRange.value = data.price_range;
    }
  } catch (error) {
    console.error('Error fetching buyer preferences:', error);
  }
};

const submitForm = async () => {
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await axios.post('/api/buyers_preference', {
      preferred_weight: preferredWeight.value,
      age_of_pigs: ageOfPigs.value,
      price_range: priceRange.value
    }, {
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    });

    if (response.status !== 200) {
      throw new Error('Network response was not ok');
    }

    const data = response.data;
    console.log('Form submitted successfully:', data);
    // Re-fetch the buyer preferences to reflect the updated details
    await fetchBuyerPreferences();
  } catch (error) {
    console.error('Error submitting form:', error);
  }
};

onMounted(() => {
  fetchUserId();
  fetchBuyerPreferences();
});
</script>

<style>
/* Add any necessary styles here */
</style>