export interface ListingData {
  title: string;
  description: string;
  price: number;
  category: string;
  status: boolean;
  date_posted?: Date;
}

export type OptionalListingData = Partial<ListingData>;
