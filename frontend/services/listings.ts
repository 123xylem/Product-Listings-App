// services/listings.ts
import type { ListingData, OptionalListingData } from "@/types/listings";

interface ApiResponse<T> {
  success: boolean;
  data: T;
}

export default class ListingsService {
  private baseUrl: string;

  constructor() {
    const config = useRuntimeConfig();
    this.baseUrl = config.public.apiBaseUrl as string;
  }

  async fetchItems(id?: number): Promise<ListingData[]> {
    try {
      const endpoint = id
        ? `${this.baseUrl}/listings/${id}`
        : `${this.baseUrl}/listings`;

      const data = await $fetch<ApiResponse<ListingData[]>>(endpoint);
      console.log(data, "DATA HREE");
      return data.data ?? [];
    } catch (error) {
      console.error("Error fetching items:", error);
      return [];
    }
  }

  async addItem(listingData: ListingData): Promise<ListingData> {
    try {
      const data = await $fetch<ApiResponse<ListingData>>(
        `${this.baseUrl}/listings`,
        {
          method: "POST",
          body: {
            title: listingData.title,
            description: listingData.description,
            price: listingData.price,
            status: listingData.status,
            category: listingData.category,
            date_posted: new Date().toISOString(),
          },
        }
      );
      if (!data.data) throw new Error("Failed to create listing");
      return data.data;
    } catch (error) {
      console.error("Error creating item:", error);
      throw error;
    }
  }

  async updateItem(
    id: number,
    listingData: OptionalListingData
  ): Promise<ListingData> {
    try {
      const data = await $fetch<ApiResponse<ListingData>>(
        `${this.baseUrl}/listings/${id}`,
        {
          method: "PUT",
          body: {
            ...Object.entries(listingData).reduce(
              (acc, [key, value]) => ({
                ...acc,
                [key]: value ?? "",
              }),
              {}
            ),
          },
        }
      );
      if (!data.data) throw new Error("Failed to update listing");
      return data.data;
    } catch (error) {
      console.error("Error updating item:", error);
      throw error;
    }
  }

  async deleteItem(id: number): Promise<ListingData> {
    try {
      const data = await $fetch<ApiResponse<ListingData>>(
        `${this.baseUrl}/listings/${id}`,
        {
          method: "DELETE",
        }
      );
      if (!data.data) throw new Error("Failed to delete listing");
      return data.data;
    } catch (error) {
      console.error("Error deleting item:", error);
      throw error;
    }
  }
}
