<template>
  <div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Listings</h1>
      <NuxtLink
        to="/create"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
      >
        Create New Listing
      </NuxtLink>
    </div>

    <div v-if="loading" class="text-center py-8">
      <div
        class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"
      ></div>
    </div>

    <div v-else-if="error" class="text-red-500 text-center py-8">
      {{ error }}
    </div>

    <div
      v-else
      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 rounded-md p-4"
    >
      <div class="filter-block flex gap-4 w-full col-span-full mb-4">
        <div class="mt-1">
          <select
            v-model="filters.category"
            class="text-sm text-gray-500 bg-gray-50 px-2 py-1 rounded border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
          >
            <option default value="">All Categories</option>
            <option value="Electronics">Electronics</option>
            <option value="Vehicle">Vehicle</option>
            <option value="Sports">Sports</option>
            <option value="Property">Property</option>
          </select>
        </div>
        <div class="mt-1">
          <input
            placeholder="Filter by title"
            v-model="filters.title"
            class="text-sm text-gray-500 bg-gray-50 px-2 py-1 rounded border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
          />
        </div>
      </div>

      <ListingCard
        v-for="listing in listings.items"
        :key="listing.id"
        :listing="listing"
        @delete="handleDelete"
      />
    </div>

    <!-- Pagination controls -->
    <div v-if="totalPages > 1" class="mt-8 flex justify-center gap-2">
      <button
        v-for="page in totalPages"
        :key="page"
        @click="setPage(page)"
        :class="[
          'px-4 py-2 rounded-md',
          currentPage === page
            ? 'bg-blue-500 text-white'
            : 'bg-gray-200 hover:bg-gray-300',
        ]"
      >
        {{ page }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, computed, ref, watch } from "vue";
import { useListings } from "../composables/useListings";
import { useFilters } from "../composables/useFilters";
import type { OptionalListingData } from "../types/listings";
import { useDebounceFn } from "@vueuse/core";

const {
  listings,
  fetchListings,
  loading,
  error,
  currentPage,
  totalPages,
  setPage,
} = useListings();

const filters = useFilters();

onMounted(() => {
  fetchListings();
});
</script>
<style>
.delete-button {
  display: none !important;
}
</style>
