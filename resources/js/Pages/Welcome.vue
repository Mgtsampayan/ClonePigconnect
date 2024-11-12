<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
  <div class="relative pt-16 pb-32 flex content-center items-center justify-center" style="min-height: 75vh;">
    <div class="absolute top-0 w-full h-full bg-center bg-cover" style="background-image: url('pig.png');">
      <span id="blackOverlay" class="w-full h-full absolute opacity-75 bg-black"></span>
    </div>

    <!-- Header Section with Title and Links -->
    <header class="absolute top-0 left-0 w-full flex justify-between items-center p-4">
      <!-- Title on the Far Left -->
      <div class="text-white font-bold text-xl">
        PigConnect
      </div>
      
      <!-- Links on the Far Right -->
      <div class="flex space-x-4">
        <Link href="/login" class="text-white font-bold py-2 px-4 rounded bg-transparent hover:underline">Login</Link>
        <Link  href="/register" class="text-white font-bold py-2 px-4 rounded bg-transparent hover:underline">Register</Link>
      </div>
    </header>
    
    <div class="container relative mx-auto">
      <div class="items-center flex flex-wrap">
        <div class="w-full lg:w-6/12 px-4 ml-auto mr-auto text-center">
          <div class="pr-12">
            <h1 class="text-white font-semibold text-5xl">PigConnect: Intelligent Pig Farming Management System</h1>
            <p class="mt-4 text-lg text-gray-300">
                Pigconnect is a comprehensive web app designed for pig farmers, enabling seamless tracking of herd health, growth, and productivity. Optimize your operations and stay connected to your flock with ease!
            </p>
          </div>
        </div>
      </div>
      
      <!-- Image Element with Error Handling -->
      <img 
        src="your-image-url.png" 
        alt="Descriptive alt text" 
        id="screenshot-container"
        @error="handleImageError"
        class="mx-auto mt-4" 
      />
      
    </div>
    
    <div class="top-auto bottom-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden" style="height: 70px; transform: translateZ(0px);">
      <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" version="1.1" viewBox="0 0 2560 100" x="0" y="0">
        <polygon class="text-gray-300 fill-current" points="2560 0 2560 100 0 100"></polygon>
      </svg>
    </div>
  </div>
</template>

<script>
export default {};
</script>
