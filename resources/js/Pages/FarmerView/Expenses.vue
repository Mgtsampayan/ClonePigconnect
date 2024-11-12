<template>
    <div>
      <AuthenticatedLayout>
        <template #header>
          <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Expenses</h2>
        </template>
        <main>
          <div class="flex justify-center">
            <div class="w-[75%]">
              <ExpenseModal @expense-added="fetchExpenses" />
              <table class="min-w-full divide-y divide-gray-200 mt-4">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Purchase</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="expense in expenses" :key="expense.id">
                    <td class="px-6 py-4 whitespace-nowrap">{{ expense.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ expense.cost }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ expense.date_of_purchase }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ expense.type }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </AuthenticatedLayout>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import ExpenseModal from '@/Pages/FarmerView/ExpensesModal.vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  
  const expenses = ref([]);
  
  const fetchExpenses = async () => {
    try {
      const response = await fetch('/api/expenses', {
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      });
  
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
  
      const data = await response.json();
      expenses.value = data;
    } catch (error) {
      console.error('Error fetching expenses:', error);
    }
  };
  
  onMounted(() => {
    fetchExpenses();
  });
  </script>
  
  <style>
  /* Add any necessary styles here */
  </style>