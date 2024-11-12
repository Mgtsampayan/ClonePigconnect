<!-- ProfilePicture.vue -->
<script setup>
import { ref, defineProps } from 'vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    initialImage: {
        type: String,
        default: ''
    },
});

const profilePicture = ref(null);
const imageUrl = ref(props.initialImage || ''); // Use the initial image if provided

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imageUrl.value = e.target.result; // Set the image URL to display
        };
        reader.readAsDataURL(file);
        profilePicture.value = file; // Store the file for form submission
    }
};

const submitProfilePicture = () => {
    // Here you would handle the form submission
    console.log('Profile picture file:', profilePicture.value);
    // You might want to reset the input after submission
};
</script>

<template>
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile Picture</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Update your profile picture by uploading a new image.
        </p>
        <div class="mt-2">
            <img
                v-if="imageUrl"
                :src="imageUrl"
                alt="Profile Picture"
                class="h-32 w-32 rounded-full object-cover"
            />
            <input
                type="file"
                accept="image/*"
                @change="handleFileChange"
                class="mt-2 block w-full"
            />
            <InputError class="mt-2" :message="null" /> <!-- Replace with appropriate error handling -->
        </div>
        <div class="mt-4">
            <PrimaryButton @click="submitProfilePicture">Save Profile Picture</PrimaryButton>
        </div>
    </div>
</template>
