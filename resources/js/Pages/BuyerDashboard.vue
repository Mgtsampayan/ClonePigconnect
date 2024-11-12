<template>
  <div>
    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Buyer Dashboard</h2>
      </template>
      <main>
        <div class="flex justify-center">
          <div class="w-[75%]">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Recommended Pigs</h3>
            <div v-if="recommendedPigs.length === 0" class="text-center text-gray-700 dark:text-gray-300">
              No recommendations available.
            </div>
            <div v-else>
              <div v-for="pig in recommendedPigs" :key="pig.id" class="mb-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <div class="flex items-center mb-4">
                  <img :src="`/storage/${pig.image}`" alt="Pig Image" class="w-24 h-24 rounded-lg mr-4 object-cover">
                  <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ pig.id }}</h3>
                    <button @click="showPigDetails(pig)" class="text-blue-500 hover:underline">View Details</button>
                  </div>
                </div>
                <div class="mb-2">
                  <span class="font-semibold text-gray-700 dark:text-gray-300">Weight: </span>
                  <span class="text-gray-700 dark:text-gray-300">{{ pig.weight }} kg.</span>
                </div>
                <div class="mb-2">
                  <span class="font-semibold text-gray-700 dark:text-gray-300">Age:</span>
                  <span class="text-gray-700 dark:text-gray-300">{{ computeAgeInMonths(pig.date_of_birth) }} months</span>
                </div>
                <div class="mb-2">
                  <span class="font-semibold text-gray-700 dark:text-gray-300">Price Range:</span>
                  <span class="text-gray-700 dark:text-gray-300">{{ pig.min_price_per_kilo }} - {{ pig.max_price_per_kilo }} per kilo</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </AuthenticatedLayout>

    <!-- Pig Details Modal -->
    <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-75">
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-[90%] md:w-[50%] h-[80%] overflow-y-auto">
        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4 text-center">Pig Details</h3>
        <div class="mb-4 flex justify-center">
          <img :src="`/storage/${selectedPig.image}`" alt="Pig Image" class="w-48 h-48 rounded-lg object-cover">
        </div>
    
        <div class="mb-2 text-center">
          <span class="text-xl font-semibold text-gray-700 dark:text-gray-300">Owner: </span>
          <span class="text-xl text-gray-700 dark:text-gray-300"> {{ selectedPig.owner }}</span>
        </div>
        <div class="mb-2 text-center">
          <span class="text-xl font-semibold text-gray-700 dark:text-gray-300">Weight:</span>
          <span class="text-xl text-gray-700 dark:text-gray-300">{{ selectedPig.weight }} kg</span>
        </div>
        <div class="mb-2 text-center">
          <span class="text-xl font-semibold text-gray-700 dark:text-gray-300">Age:</span>
          <span class="text-xl text-gray-700 dark:text-gray-300">{{ computeAgeInMonths(selectedPig.date_of_birth) }} months</span>
        </div>
        <div class="mb-2 text-center">
          <span class="text-xl font-semibold text-gray-700 dark:text-gray-300">Price Range:</span>
          <span class="text-xl text-gray-700 dark:text-gray-300">{{ selectedPig.min_price_per_kilo }} - {{ selectedPig.max_price_per_kilo }} per kilo</span>
        </div>
        <div class="mb-2 text-center">
          <span class="text-xl font-semibold text-gray-700 dark:text-gray-300">Location:</span>
          <span class="text-xl text-gray-700 dark:text-gray-300">{{ selectedPig.location }}</span>
        </div>
        <div id="map" class="w-full h-64 mb-4"></div>

        <!-- Feedback Section -->
        <div class="mb-4">
          <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Feedback</h4>
          <div v-if="feedback.length === 0" class="text-center text-gray-700 dark:text-gray-300">
            No feedback available.
          </div>
          <div v-else>
            <div v-for="item in feedback" :key="item.id" class="mb-2 p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
              <div class="flex justify-between">
                <span class="font-semibold text-gray-800 dark:text-gray-200">{{ item.user.name }}</span>
                <span class="text-gray-600 dark:text-gray-400">{{ item.rating }} / 5</span>
              </div>
              <p class="text-gray-700 dark:text-gray-300">{{ item.comment }}</p>
            </div>
          </div>
        </div>

        <!-- Add Feedback Form -->
        <div class="mb-4">
          <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Add Feedback</h4>
          <form @submit.prevent="submitFeedback">
            <div class="mb-2">
              <label for="rating" class="block text-gray-700 dark:text-gray-300">Rating:</label>
              <select id="rating" v-model="newFeedback.rating" class="w-full p-2 border rounded-lg">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
            </div>
            <div class="mb-2">
              <label for="comment" class="block text-gray-700 dark:text-gray-300">Comment:</label>
              <textarea id="comment" v-model="newFeedback.comment" class="w-full p-2 border rounded-lg"></textarea>
            </div>
            <button type="submit" class="mt-2 bg-blue-500 text-white p-2.5 rounded-lg focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:focus:ring-blue-800">Submit</button>
          </form>
        </div>

        <div class="flex justify-center">
          <button @click="closeModal" class="mt-4 bg-blue-500 text-white p-2.5 rounded-lg focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:focus:ring-blue-800">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const recommendedPigs = ref([]);
const showModal = ref(false);
const selectedPig = ref(null);
const feedback = ref([]);
const newFeedback = ref({
  rating: 5,
  comment: ''
});

const fetchRecommendations = async () => {
  try {
    const response = await fetch('/api/recommendations', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    });
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    recommendedPigs.value = data;
  } catch (error) {
    console.error('Error fetching recommendations:', error);
    alert('Failed to fetch recommendations');
  }
};

const computeAgeInMonths = (dateOfBirth) => {
  const birthDate = new Date(dateOfBirth);
  const now = new Date();
  return (now.getFullYear() - birthDate.getFullYear()) * 12 + (now.getMonth() - birthDate.getMonth());
};

const showPigDetails = async (pig) => {
  selectedPig.value = pig;
  showModal.value = true;
  await fetchFarmDetails(pig.user_id);
  await fetchFeedback(pig.id);
  await trackInteraction(pig.id);
};

const closeModal = () => {
  showModal.value = false;
};

const fetchFarmDetails = async (userId) => {
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await fetch(`/api/pigfarminformation/buyer?user_id=${userId}`, {
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    });
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const farmDetails = await response.json();
    console.log(farmDetails); // Debugging: Log farm details
    selectedPig.value.min_price_per_kilo = farmDetails.min_price_per_kilo;
    selectedPig.value.max_price_per_kilo = farmDetails.max_price_per_kilo;
    selectedPig.value.owner = farmDetails.owner_name;
    selectedPig.value.location = farmDetails.location;
    const latitude = parseFloat(farmDetails.latitude);
    const longitude = parseFloat(farmDetails.longitude);
    if (isFinite(latitude) && isFinite(longitude)) {
      initMap(latitude, longitude);
    } else {
      console.error('Invalid latitude or longitude:', latitude, longitude);
      alert('Invalid location data');
    }
  } catch (error) {
    console.error('Error fetching farm details:', error);
    alert('Failed to fetch farm details');
  }
};

const fetchFeedback = async (pigId) => {
  try {
    const response = await fetch(`/api/pigs/${pigId}/feedback`, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    });
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    feedback.value = data;
  } catch (error) {
    console.error('Error fetching feedback:', error);
    alert('Failed to fetch feedback');
  }
};

const submitFeedback = async () => {
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    console.log('Submitting feedback for pig ID:', selectedPig.value.id); // Debugging: Log pig ID
    const response = await fetch(`/api/pigs/${selectedPig.value.id}/feedback`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify(newFeedback.value)
    });
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    feedback.value.push(data);
    newFeedback.value.rating = 5;
    newFeedback.value.comment = '';
  } catch (error) {
    console.error('Error submitting feedback:', error);
    alert('Failed to submit feedback');
  }
};

const trackInteraction = async (pigId) => {
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await fetch('/api/track_interaction', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify({  pig_id: pigId })
    });
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    console.log('Interaction tracked successfully');
  } catch (error) {
    console.error('Error tracking interaction:', error);
  }
};

const initMap = (latitude, longitude) => {
  const map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: latitude, lng: longitude },
    zoom: 15,
  });
  new google.maps.Marker({
    position: { lat: latitude, lng: longitude },
    map,
    title: "Pig Location",
  });
};

onMounted(() => {
  fetchRecommendations();
});
</script>

<style>
.modal {
  display: block;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgb(0,0,0);
  background-color: rgba(0,0,0,0.4);
}
.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}
.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>