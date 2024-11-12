<template>
  <div>
    <AuthenticatedLayout>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Breeding Records</h2>
      </template>
      <main>
        <div class="flex justify-center">
          <div class="w-[75%]">
            <button @click="showAddBreedingForm = true" class="mb-4 bg-blue-500 text-white p-2.5 rounded-lg focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:focus:ring-blue-800">Add Breeding Record</button>

            <!-- Add Breeding Record Form -->
            <div v-if="showAddBreedingForm" class="modal">
              <div class="modal-content">
                <span class="close" @click="closeAddBreedingForm">&times;</span>
                <h3>Add Breeding Record</h3>
                <form @submit.prevent="addBreedingRecord">
                  <div class="mb-4">
                    <label for="sowId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sow (Female Pig)</label>
                    <select id="sowId" v-model="breedingForm.sow_id" class="mt-1 block w-full p-2.5 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                      <option v-for="pig in femalePigs" :key="pig.PigId" :value="pig.PigId">{{ pig.PigId }}</option>
                    </select>
                  </div>
                  <div class="mb-4">
                    <label for="boarId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Boar (Male Pig)</label>
                    <select id="boarId" v-model="breedingForm.boar_id" class="mt-1 block w-full p-2.5 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                      <option v-for="pig in malePigs" :key="pig.PigId" :value="pig.PigId">{{ pig.PigId }}</option>
                    </select>
                  </div>
                  <div class="mb-4">
                    <label for="dateOfBreeding" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Breeding</label>
                    <input type="date" id="dateOfBreeding" v-model="breedingForm.date_of_breeding" @change="computeExpectedFarrowingDate" class="mt-1 block w-full p-2.5 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                  </div>
                  <div class="mb-4">
                    <label for="expectedFarrowingDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expected Farrowing Date</label>
                    <input type="date" id="expectedFarrowingDate" v-model="breedingForm.expected_farrowing_date" class="mt-1 block w-full p-2.5 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                  </div>
                  <button type="submit" class="w-full bg-blue-500 text-white p-2.5 rounded-lg focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:focus:ring-blue-800">Submit</button>
                </form>
              </div>
            </div>

            <!-- Breeding Records Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full bg-white dark:bg-gray-800">
                <thead>
                  <tr>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Sow ID</th>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Boar ID</th>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Date of Breeding</th>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Expected Farrowing Date</th>
                    <th class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="record in breedingRecords" :key="record.id">
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ record.sow_id }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ record.boar_id }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ record.date_of_breeding }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">{{ record.expected_farrowing_date }}</td>
                    <td class="py-2 px-4 border-b border-gray-200 dark:border-gray-700">
                      <button @click="deleteBreedingRecord(record.id)" class="bg-red-500 text-white p-2.5 rounded-lg focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:focus:ring-red-800">Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
    </AuthenticatedLayout>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const breedingRecords = ref([]);
const showAddBreedingForm = ref(false);
const breedingForm = reactive({
  sow_id: '',
  boar_id: '',
  date_of_breeding: '',
  expected_farrowing_date: ''
});
const malePigs = ref([]);
const femalePigs = ref([]);

const fetchBreedingRecords = async () => {
  try {
    const response = await fetch('/api/breeding-records', {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      credentials: 'include'
    });
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    breedingRecords.value = data;
  } catch (error) {
    console.error('Error fetching breeding records:', error);
  }
};

const fetchPigs = async () => {
  try {
    const response = await fetch('/api/pigs', {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      credentials: 'include'
    });
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    malePigs.value = data.filter(pig => pig.gender === 'male');
    console.log(malePigs);
    femalePigs.value = data.filter(pig => pig.gender === 'female');
    console.log(femalePigs);
  } catch (error) {
    console.error('Error fetching pigs:', error);
  }
};

const computeExpectedFarrowingDate = () => {
  const dateOfBreeding = new Date(breedingForm.date_of_breeding);
  const gestationPeriod = 114; // Gestation period for pigs in days
  const expectedFarrowingDate = new Date(dateOfBreeding);
  expectedFarrowingDate.setDate(dateOfBreeding.getDate() + gestationPeriod);
  breedingForm.expected_farrowing_date = expectedFarrowingDate.toISOString().split('T')[0];
};

const addBreedingRecord = async () => {
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = await fetch('/api/breeding-records', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify(breedingForm)
    });

    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    const data = await response.json();
    breedingRecords.value.push(data);
    closeAddBreedingForm();
  } catch (error) {
    console.error('Error adding breeding record:', error);
  }
};

const deleteBreedingRecord = async (id) => {
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    await fetch(`/api/breeding-records/${id}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      credentials: 'include'
    });
    breedingRecords.value = breedingRecords.value.filter(record => record.id !== id);
  } catch (error) {
    console.error('Error deleting breeding record:', error);
  }
};

const closeAddBreedingForm = () => {
  breedingForm.sow_id = '';
  breedingForm.boar_id = '';
  breedingForm.date_of_breeding = '';
  breedingForm.expected_farrowing_date = '';
  showAddBreedingForm.value = false;
};

onMounted(() => {
  fetchPigs(); 
  fetchBreedingRecords();
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
  color: #000;
}

.dark .modal-content {
  background-color: #333;
  color: #fff;
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