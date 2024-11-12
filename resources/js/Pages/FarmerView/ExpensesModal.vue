<template>
    <div>
      <button @click="openModal" class="bg-blue-500 text-white p-2.5 rounded-lg focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:focus:ring-blue-800">Add Expense</button>
  
      <transition name="modal">
        <div v-if="isOpen" class="fixed z-10 inset-0 overflow-y-auto">
          <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
              <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
  
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
  
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
              <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Add Expense</h3>
                    <div class="mt-2">
                      <form @submit.prevent="submitExpenseForm">
                        <div class="mb-4">
                          <label for="expenseName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                          <input type="text" id="expenseName" v-model="expenseName" class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                        </div>
  
                        <div class="mb-4">
                          <label for="expenseCost" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cost</label>
                          <input type="number" id="expenseCost" v-model="expenseCost" class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                        </div>
  
                        <div class="mb-4">
                          <label for="expenseDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Purchase</label>
                          <input type="date" id="expenseDate" v-model="expenseDate" class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                        </div>
  
                        <div class="mb-4">
                          <label for="expenseType" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                          <select id="expenseType" v-model="expenseType" class="mt-1 block w-full p-2.5 bg-gray-100 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="medication">Medication</option>
                            <option value="food">Food</option>
                            <option value="transportation">Transportation</option>
                          </select>
                        </div>
  
                        <div class="mt-5 sm:mt-6">
                          <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">Submit</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button @click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </template>
  
  <script setup>
  import { ref, defineEmits } from 'vue';
  
  const isOpen = ref(false);
  const expenseName = ref('');
  const expenseCost = ref('');
  const expenseDate = ref('');
  const expenseType = ref('');
  
  const emit = defineEmits(['expense-added']);
  
  const openModal = () => {
    isOpen.value = true;
  };
  
  const closeModal = () => {
    isOpen.value = false;
  };
  
  const submitExpenseForm = async () => {
    try {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const response = await fetch('/api/expenses', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({
          name: expenseName.value,
          cost: expenseCost.value,
          date_of_purchase: expenseDate.value,
          type: expenseType.value,
        }),
      });
  
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
  
      const data = await response.json();
      console.log('Expense submitted successfully:', data);
      closeModal();
      emit('expense-added'); // Emit event to notify parent component
    } catch (error) {
      console.error('Error submitting expense:', error);
    }
  };
  </script>
  
  <style>
  /* Add any necessary styles here */
  </style>