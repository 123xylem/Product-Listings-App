<template>
  <div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Listings</h1>
      <NuxtLink
        to="/listings/create"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
      >
        Create New Listing
      </NuxtLink>
    </div>

    <div v-if="loading" class="text-center py-8">Loading...</div>

    <div v-else-if="error" class="text-red-500 text-center py-8">
      {{ error }}
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <ListingCard
        v-for="listing in listings"
        :key="listing.id"
        :listing="listing"
        @delete="handleDelete"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from "vue";
import { useListings } from "../composables/useListings";
import type { OptionalListingData } from "../types/listings";
const { listings, loading, error, fetchListings, deleteListing } =
  useListings();

// Fetch listings on component mount
onMounted(() => {
  fetchListings();
});

const handleDelete = async (id: number) => {
  try {
    await deleteListing(id);
  } catch (err) {
    console.error(err, "err in delete");
  }
};
</script>
