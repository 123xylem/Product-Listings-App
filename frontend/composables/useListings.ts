import { ref, computed } from "vue";
import type { ListingData, OptionalListingData } from "@/types/listings";
import ListingsService from "@/services/listings";
import { useAsyncData } from "#app";
import { useFilters } from "@/composables/useFilters";

export const useListings = () => {
  const service = new ListingsService();
  const error = ref<string | null>(null);
  const loading = ref(false);
  const currentPage = ref(1);
  const itemsPerPage = 6;
  const filters = useFilters();

  // Fetch listings
  const { data: listings, refresh } = useAsyncData(
    "listings",
    () => service.fetchItems(),
    {
      server: true,
      default: () => [],
    }
  );

  //watch filters for change to trigger full search
  watch(
    filters,
    () => {
      currentPage.value = 1;
    },
    { deep: true }
  );

  const filteredAndPaginatedListings = computed(() => {
    const filtered =
      listings.value?.filter((listing) => {
        if (listing.status !== "Active") return false;

        if (
          filters.value.category &&
          listing.category !== filters.value.category
        ) {
          return false;
        }

        // Use debounced title for filtering
        if (
          filters.value.debouncedTitle &&
          !listing.title.toLowerCase().includes(filters.value.debouncedTitle)
        ) {
          return false;
        }

        return true;
      }) || [];

    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;

    return {
      items: filtered.slice(start, end),
      total: filtered.length,
    };
  });

  // Add pagination computed properties
  const totalPages = computed(() => {
    return Math.ceil(
      (filteredAndPaginatedListings.value?.total || 0) / itemsPerPage
    );
  });

  const setPage = (page: number) => {
    currentPage.value = page;
  };

  const fetchListings = async () => {
    loading.value = true;
    try {
      await refresh();
    } catch (err) {
      error.value = "Failed to fetch listings";
      console.error(err);
    } finally {
      loading.value = false;
    }
  };

  const fetchSingleListing = async (id: number) => {
    console.log("Fetching single listing:", id);
    try {
      const listing = await service.fetchItems(id);
      return listing;
    } catch (err) {
      error.value = "Failed to fetch listing";
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const createListing = async (data: ListingData) => {
    loading.value = true;
    try {
      const newListing = await service.addItem(data);
      listings.value.push(newListing);
      return newListing;
    } catch (err) {
      error.value = "Failed to create listing";
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const updateListing = async (id: number, data: OptionalListingData) => {
    loading.value = true;
    try {
      const updated = await service.updateItem(id, data);
      return updated;
    } catch (err) {
      error.value = "Failed to update listing";
      throw err;
    } finally {
      loading.value = false;
    }
  };

  const deleteListing = async (id: number) => {
    loading.value = true;
    try {
      await service.deleteItem(id);
    } catch (err) {
      error.value = "Failed to delete listing";
      throw err;
    } finally {
      loading.value = false;
    }
  };

  return {
    listings: filteredAndPaginatedListings,
    loading,
    error,
    currentPage,
    totalPages,
    setPage,
    fetchListings,
    fetchSingleListing,
    createListing,
    updateListing,
    deleteListing,
  };
};
