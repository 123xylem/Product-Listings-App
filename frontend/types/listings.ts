export interface ListingData {
  id?: number;
  title: string;
  description: string;
  price: number;
  category: string;
  status: string;
  date_posted?: Date;
}

export type OptionalListingData = Partial<ListingData>;
