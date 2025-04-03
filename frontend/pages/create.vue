<template>
  <div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Create New Listing</h1>

    <form
      @submit.prevent="handleSubmit"
      class="space-y-6 border shadow-lg bg-blue-50 p-4 rounded-md"
    >
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
        <label for="status" class="block text-sm font-medium text-gray-700"
          >Status</label
        >
        <select
          id="status"
          v-model="form.status"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          :class="{ 'border-red-500': errors.status }"
        >
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
        <p v-if="errors.status" class="mt-1 text-sm text-red-600">
          {{ errors.status }}
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
        <label for="price" class="block text-sm font-medium text-gray-700"
          >Price</label
        >
        <input
          id="price"
          v-model="form.price"
          type="number"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
          :class="{ 'border-red-500': errors.price }"
        />
        <p v-if="errors.price" class="mt-1 text-sm text-red-600">
          {{ errors.price }}
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
import { useListings } from "../composables/useListings";
const { createListing } = useListings();

const router = useRouter();
const loading = ref(false);

const form = ref({
  title: "",
  description: "",
  category: "",
  price: "",
  status: "Active",
});

const errors = ref({
  title: "",
  description: "",
  category: "",
  price: "",
  status: "",
});

const categories = ["Electronics", "Vehicle", "Property", "Sports"];

const validateForm = () => {
  errors.value = {
    title: "",
    description: "",
    category: "",
    price: "",
    status: "",
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

  if (!form.value.price) {
    errors.value.price = "Price is required";
    return false;
  }

  if (!form.value.status) {
    errors.value.status = "Status is required";
    return false;
  }

  return true;
};

const handleSubmit = async () => {
  if (!validateForm()) return;

  loading.value = true;
  try {
    const newListing = await createListing(form.value);
    if (newListing) {
      router.push(`/${newListing.id}?isNew=true`);
    }
  } catch (error) {
    console.error("Error submitting form:", error);
  } finally {
    loading.value = false;
  }
};
</script>
