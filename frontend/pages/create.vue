<template>
  <div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Create New Listing</h1>

    <form @submit.prevent="handleSubmit" class="space-y-6">
      <div>
        <label for="title" class="block text-sm font-medium text-gray-700"
          >Title</label
        >
        <input
          id="title"
          v-model="form.title"
          type="text"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          :class="{ 'border-red-500': errors.title }"
        />
        <p v-if="errors.title" class="mt-1 text-sm text-red-600">
          {{ errors.title }}
        </p>
      </div>

      <div>
        <label for="description" class="block text-sm font-medium text-gray-700"
          >Description</label
        >
        <textarea
          id="description"
          v-model="form.description"
          rows="4"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          :class="{ 'border-red-500': errors.description }"
        ></textarea>
        <p v-if="errors.description" class="mt-1 text-sm text-red-600">
          {{ errors.description }}
        </p>
      </div>

      <div>
        <label for="category" class="block text-sm font-medium text-gray-700"
          >Category</label
        >
        <select
          id="category"
          v-model="form.category"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          :class="{ 'border-red-500': errors.category }"
        >
          <option value="">Select a category</option>
          <option
            v-for="category in categories"
            :key="category"
            :value="category"
          >
            {{ category }}
          </option>
        </select>
        <p v-if="errors.category" class="mt-1 text-sm text-red-600">
          {{ errors.category }}
        </p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Image</label>
        <div class="mt-1 flex items-center">
          <div class="relative">
            <input
              type="file"
              accept="image/*"
              @change="handleImageUpload"
              class="hidden"
              ref="fileInput"
            />
            <button
              type="button"
              @click="$refs.fileInput.click()"
              class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Choose Image
            </button>
          </div>
          <div v-if="imagePreview" class="ml-4">
            <img
              :src="imagePreview"
              class="h-20 w-20 object-cover rounded-md"
            />
          </div>
        </div>
      </div>

      <div class="flex justify-end space-x-4">
        <NuxtLink
          to="/"
          class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
        >
          Cancel
        </NuxtLink>
        <button
          type="submit"
          :disabled="loading"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
        >
          {{ loading ? "Saving..." : "Save Listing" }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "nuxt/app";

const router = useRouter();
const loading = ref(false);
const imagePreview = ref("");
const fileInput = ref<HTMLInputElement | null>(null);

const form = ref({
  title: "",
  description: "",
  category: "",
  image: null as File | null,
});

const errors = ref({
  title: "",
  description: "",
  category: "",
});

const categories = ["Electronics", "Fashion", "Home", "Sports", "Other"];

const validateForm = () => {
  errors.value = {
    title: "",
    description: "",
    category: "",
  };

  if (!form.value.title) {
    errors.value.title = "Title is required";
    return false;
  }

  if (!form.value.description) {
    errors.value.description = "Description is required";
    return false;
  }

  if (!form.value.category) {
    errors.value.category = "Category is required";
    return false;
  }

  return true;
};

const handleImageUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    const file = target.files[0];
    form.value.image = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

const handleSubmit = async () => {
  if (!validateForm()) return;

  loading.value = true;
  try {
    // Simulate API call
    await new Promise((resolve) => setTimeout(resolve, 1000));
    // Handle successful submission
    router.push("/");
  } catch (error) {
    console.error("Error submitting form:", error);
  } finally {
    loading.value = false;
  }
};
</script>
