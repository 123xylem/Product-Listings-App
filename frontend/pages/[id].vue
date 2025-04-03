<template>
  <div class="max-w-4xl mx-auto">
    <div
      v-if="showNewMessage"
      class="fixed top-4 left-4 bg-green-500 z-50 text-white px-6 py-3 rounded-md shadow-lg"
    >
      Listing created successfully!
    </div>
    <div v-if="loading" class="flex justify-center items-center h-64">
      <div
        class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"
      ></div>
    </div>
    <div v-else-if="listing" class="">
      <h1 class="text-2xl mt-2 font-bold text-gray-900">
        Listing: {{ listing.title }}
      </h1>

      <div class="bg-gradient-to-br from-gray-50 mt-4 to-white">
        <div
          class="bg-white p-6 shadow-lg rounded-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow duration-200"
        >
          <div class="flex justify-between items-start">
            <div class="flex-grow">
              <!-- Title -->
              <div class="relative">
                <input
                  v-if="isEditing"
                  v-model="editedListing.title"
                  class="text-2xl font-bold text-gray-900 w-full bg-gray-50 px-2 py-1 rounded border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                />
                <h1 v-else class="text-2xl font-bold text-gray-900">
                  {{ listing.title }}
                </h1>
              </div>

              <!-- Category -->
              <div class="mt-1">
                <select
                  v-if="isEditing"
                  v-model="editedListing.category"
                  class="text-sm text-gray-500 bg-gray-50 px-2 py-1 rounded border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                >
                  <option value="Electronics">Electronics</option>
                  <option value="Furniture">Furniture</option>
                  <option value="Sports">Sports</option>
                  <option value="Property">Property</option>
                </select>
                <p v-else class="text-sm text-gray-500">
                  Category: {{ listing.category }}
                </p>
              </div>
            </div>
            <!-- Actions -->
            <div class="flex space-x-3">
              <button
                v-if="!isEditing"
                @click="startEditing"
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
              >
                Edit
              </button>
              <template v-else>
                <button
                  @click="saveChanges"
                  :disabled="saving"
                  class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                >
                  {{ saving ? "Saving..." : "Save" }}
                </button>
                <button
                  @click="cancelEditing"
                  class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Cancel
                </button>
              </template>
              <button
                @click="showDeleteDialog = true"
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
              >
                Delete
              </button>
            </div>
          </div>

          <!-- Description -->
          <div class="mt-6">
            <h2 class="text-lg font-medium text-gray-900">Description</h2>
            <textarea
              v-if="isEditing"
              v-model="editedListing.description"
              rows="4"
              class="mt-2 w-full bg-gray-50 px-2 py-1 rounded border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
            ></textarea>
            <p v-else class="mt-2 text-gray-600">{{ listing.description }}</p>
          </div>

          <!-- Price -->
          <div class="mt-4" v-if="isEditing">
            <label class="text-sm text-gray-500">Price:</label>
            <div class="relative mt-1 w-32">
              <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
              >
                <span class="text-gray-500 sm:text-sm">$</span>
              </div>
              <input
                type="number"
                v-model="editedListing.price"
                step="0.01"
                class="pl-7 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
              />
            </div>
          </div>

          <!-- Status -->
          <div v-if="isEditing" class="mt-1">
            <select
              v-model="editedListing.status"
              class="text-sm text-gray-500 bg-gray-50 px-2 py-1 rounded border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
            >
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
            <p class="text-sm text-gray-500">Status: {{ listing.status }}</p>
          </div>

          <div class="mt-6 text-sm text-gray-500">
            Created: {{ formatDate(listing.date_posted) }}
          </div>
        </div>
      </div>
    </div>
    <!-- Delete Confirmation Dialog -->
    <DeleteDialog
      :show="showDeleteDialog"
      :loading="deleting"
      @close="showDeleteDialog = false"
      @confirm="handleDelete"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import { useRoute, useRouter } from "nuxt/app";
import type { OptionalListingData } from "../types/listings";
import { useListings } from "../composables/useListings";
import DeleteDialog from "../components/DeleteDialog.vue";

const { updateListing, deleteListing, fetchSingleListing } = useListings();

const route = useRoute();
const router = useRouter();
const loading = ref(true);
const deleting = ref(false);
const showDeleteDialog = ref(false);
const listing = ref<OptionalListingData>(null);
const isEditing = ref(false);
const saving = ref(false);
const editedListing = ref<OptionalListingData>(null);
const showNewMessage = ref<string | null>(null);
const startEditing = () => {
  editedListing.value = { ...listing.value! };
  isEditing.value = true;
};

const cancelEditing = () => {
  editedListing.value = null;
  isEditing.value = false;
};

const saveChanges = async () => {
  if (!editedListing.value) return;

  saving.value = true;
  try {
    const id = Number(route.params.id);
    const updated = await updateListing(id, editedListing.value);

    if (updated) {
      listing.value = { ...editedListing.value };
      isEditing.value = false;
      router.push(`/${id}?isEdited=true`);
    }
  } catch (error) {
    console.error("Error updating listing:", error);
  } finally {
    saving.value = false;
  }
};

const formatDate = (date: Date) => {
  return new Date(date).toLocaleDateString();
};

const handleDelete = async () => {
  deleting.value = true;
  try {
    await deleteListing(Number(route.params.id));
    router.push("/");
  } catch (error) {
    console.error("Error deleting listing:", error);
  } finally {
    deleting.value = false;
    showDeleteDialog.value = false;
  }
};

onMounted(async () => {
  try {
    const id = Number(route.params.id);

    const response = await fetchSingleListing(id);
    listing.value = response;

    if (!response) {
      router.push("/404");
    }
  } catch (error) {
    console.error("Error fetching listing:", error);
    router.push("/404");
  } finally {
    loading.value = false;
  }
});
</script>
<style>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
