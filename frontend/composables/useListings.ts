import { ref } from "vue";
import type { ListingData, OptionalListingData } from "@/types/listings";
import ListingsService from "@/services/listings";

export const useListings = () => {
  const listings = ref<ListingData[]>([]);
  const loading = ref(false);
  const error = ref<string | null>(null);
  const service = new ListingsService();

  const fetchListings = async (
    id?: number
  ): Promise<ListingData | ListingData[] | null> => {
    loading.value = true;
    try {
      if (id) {
        listings.value = await service.fetchItems(id);
      } else {
        listings.value = await service.fetchItems();
      }
      return listings.value;
    } catch (err) {
      error.value = "Failed to fetch listings";
      console.error(err);
      return null;
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
      const index = listings.value.findIndex((l) => l.id === id);
      if (index !== -1) {
        listings.value[index] = updated;
      }
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
      listings.value = listings.value.filter((l) => l.id !== id);
    } catch (err) {
      error.value = "Failed to delete listing";
      throw err;
    } finally {
      loading.value = false;
    }
  };

  return {
    listings,
    loading,
    error,
    fetchListings,
    createListing,
    updateListing,
    deleteListing,
  };
};
