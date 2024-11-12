<template>
  <div>
    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Farm Details</h2>
      </template>
      <main>
        <div class="flex justify-center">
          <div class="w-[75%]">
            <form @submit.prevent="submitForm" class="place-content-center">
              <div class="mb-4">
                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                <div id="map" class="w-full h-64"></div>
              </div>

              <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                <input type="text" id="address" v-model="address" class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" />
              </div>

              <div class="mb-4">
                <label for="feedingType" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Feeding Type</label>
                <select id="feedingType" v-model="feedingType" class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option value="feed">Feed</option>
                  <option value="natural">Natural</option>
                </select>
              </div>

              <div class="mb-4">
                <label for="frequencyOfFeeding" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Frequency of Feeding</label>
                <input type="text" id="frequencyOfFeeding" v-model="frequencyOfFeeding" class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" />
              </div>

              <div class="mb-4">
                <label for="minPricePerKilo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum Price per Kilo</label>
                <input type="number" id="minPricePerKilo" v-model="minPricePerKilo" class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" />
              </div>

              <div class="mb-4">
                <label for="maxPricePerKilo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Maximum Price per Kilo</label>
                <input type="number" id="maxPricePerKilo" v-model="maxPricePerKilo" class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" />
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
import { ref, reactive, onMounted, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const feedingType = ref('');
const frequencyOfFeeding = ref('');
const minPricePerKilo = ref('');
const maxPricePerKilo = ref('');
const location = reactive({
  lat: 0,
  lng: 0
});
const address = ref(''); // Add a reactive property for the address

let map;
let marker;

const fetchFarmInformation = async () => {
  try {
    const response = await fetch('/api/pigfarminformation', {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}` // Ensure the token is sent with the request
      },
      credentials: 'include' // Ensure cookies are sent with the request
    });
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    console.log(data);
  
    if (data) {
      feedingType.value = data.feeding_type;
      frequencyOfFeeding.value = data.frequency_of_feeding;
      minPricePerKilo.value = data.min_price_per_kilo;
      maxPricePerKilo.value = data.max_price_per_kilo;
      location.lat = data.latitude;
      location.lng = data.longitude;

      // Update marker position
      if (marker) {
        marker.setPosition({ lat: location.lat, lng: location.lng });
        map.setCenter({ lat: location.lat, lng: location.lng });
      }

      // Perform reverse geocoding to get the address
      await reverseGeocode(location.lat, location.lng);
    }
  } catch (error) {
    console.error('Error fetching farm information:', error);
  }
};

const reverseGeocode = async (lat, lng) => {
  const apiKey = 'AIzaSyAPE3z_ByaGmKAwUDjUPFP6ZEZyyWmKvTY';
  const url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${apiKey}`;

  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    console.log('Reverse Geocoding Result:', data);
    if (data.results && data.results.length > 0) {
      address.value = data.results[0].formatted_address;
    }
  } catch (error) {
    console.error('Error performing reverse geocoding:', error);
  }
};

const geocodeAddress = async (address) => {
  const apiKey = 'AIzaSyAPE3z_ByaGmKAwUDjUPFP6ZEZyyWmKvTY';
  const url = `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${apiKey}`;

  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    console.log('Geocoding Result:', data);
    if (data.results && data.results.length > 0) {
      const position = data.results[0].geometry.location;
      location.lat = position.lat;
      location.lng = position.lng;

      // Update marker position
      if (marker) {
        marker.setPosition({ lat: location.lat, lng: location.lng });
        map.setCenter({ lat: location.lat, lng: location.lng });
      }
    }
  } catch (error) {
    console.error('Error performing geocoding:', error);
  }
};

const initMap = () => {
  map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: location.lat, lng: location.lng },
    zoom: 13
  });

  marker = new google.maps.Marker({
    position: { lat: location.lat, lng: location.lng },
    map: map,
    draggable: true
  });

  google.maps.event.addListener(marker, 'dragend', async function() {
    const position = marker.getPosition();
    location.lat = position.lat();
    location.lng = position.lng();
    map.setCenter({ lat: location.lat, lng: location.lng }); // Center the map on the new position
    await reverseGeocode(location.lat, location.lng); // Perform reverse geocoding on marker drag end
  });
};

const submitForm = async () => {
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await fetch('/api/pigfarminformation', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Authorization': `Bearer ${localStorage.getItem('token')}` // Ensure the token is sent with the request
      },
      body: JSON.stringify({
        feedingType: feedingType.value,
        frequencyOfFeeding: frequencyOfFeeding.value,
        minPricePerKilo: minPricePerKilo.value,
        maxPricePerKilo: maxPricePerKilo.value,
        location: location,
        address: address.value // Include the address in the form submission
      })
    });

    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    const data = await response.json();
    console.log('Form submitted successfully:', data);
    // Re-fetch the farm information to reflect the updated details
    await fetchFarmInformation();
  } catch (error) {
    console.error('Error submitting form:', error);
  }
};

onMounted(() => {
  fetchFarmInformation();
  initMap();
});

watch(address, async (newAddress) => {
  if (newAddress) {
    await geocodeAddress(newAddress);
  }
});
</script>

<style>
#map {
  height: 400px;
  width: 100%;
}
</style>