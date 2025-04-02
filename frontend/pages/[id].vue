<template>
  <div class="max-w-4xl mx-auto">
    <div v-if="loading" class="flex justify-center items-center h-64">
      <div
        class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"
      ></div>
    </div>

    <div
      v-else-if="listing"
      class="bg-white shadow-sm rounded-lg overflow-hidden"
    >
      <div class="p-6">
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
                <option value="Books">Books</option>
                <option value="Other">Other</option>
              </select>
              <p v-else class="text-sm text-gray-500">
                Category: {{ listing.category }}
              </p>
            </div>
          </div>

          <!-- Action Buttons -->
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

        <div class="mt-6 text-sm text-gray-500">
          Created: {{ formatDate(listing.createdAt) }}
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Dialog -->
    <TransitionRoot appear :show="showDeleteDialog" as="template">
      <Dialog as="div" @close="showDeleteDialog = false" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div
            class="flex min-h-full items-center justify-center p-4 text-center"
          >
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel
                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
              >
                <DialogTitle
                  as="h3"
                  class="text-lg font-medium leading-6 text-gray-900"
                >
                  Delete Listing
                </DialogTitle>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this listing? This action
                    cannot be undone.
                  </p>
                </div>

                <div class="mt-4 flex justify-end space-x-3">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                    @click="showDeleteDialog = false"
                  >
                    Cancel
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                    @click="handleDelete"
                    :disabled="deleting"
                  >
                    {{ deleting ? "Deleting..." : "Delete" }}
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "nuxt/app";
import type { OptionalListingData } from "../types/listings";
import { useListings } from "../composables/useListings";

const { updateListing, deleteListing, fetchListings } = useListings();

const route = useRoute();
const router = useRouter();
const loading = ref(true);
const deleting = ref(false);
const showDeleteDialog = ref(false);
const listing = ref<OptionalListingData>(null);
const isEditing = ref(false);
const saving = ref(false);
const editedListing = ref<OptionalListingData>(null);

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
    router.push("/listings");
  } catch (error) {
    console.error("Error deleting listing:", error);
  } finally {
    deleting.value = false;
    showDeleteDialog.value = false;
  }
};

// Simulate loading state
onMounted(async () => {
  try {
    const id = Number(route.params.id);
    const response = await fetchListings(id);

    if (response) {
      listing.value = {
        ...response,
      };
    } else {
      console.log("No response");
      // router.push("/404");
    }
  } catch (error) {
    console.error("Error fetching listing:", error);
    // router.push("/404");
  } finally {
    loading.value = false;
  }
});
</script>
