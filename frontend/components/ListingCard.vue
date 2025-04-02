<template>
  <div
    class="border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow"
  >
    <h2 class="text-xl font-semibold mb-2">{{ listing.title }}</h2>
    <p class="text-gray-600 mb-2">{{ listing.description }}</p>
    <div class="flex justify-between items-center">
      <span class="text-lg font-bold">${{ listing.price }}</span>
      <span
        :class="[
          'px-2 py-1 rounded text-sm',
          listing.status
            ? 'bg-green-100 text-green-800'
            : 'bg-red-100 text-red-800',
        ]"
      >
        {{ listing.status ? "Active" : "Inactive" }}
      </span>
    </div>
    <div class="mt-4 flex justify-end space-x-2">
      <NuxtLink
        :to="`/${listing.id}`"
        class="text-blue-500 hover:text-blue-700"
      >
        Edit
      </NuxtLink>
      <button
        @click="$emit('delete', listing.id)"
        class="text-red-500 hover:text-red-700"
      >
        Delete
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { ListingData } from "../types/listings";

defineProps<{
  listing: ListingData;
}>();

defineEmits<{
  (e: "delete", id: number): void;
}>();
</script>
