import { useDebounceFn } from "@vueuse/core";

export const useFilters = () => {
  const filters = useState("filters", () => ({
    category: "",
    title: "",
    debouncedTitle: "",
  }));

  watch(
    () => filters.value.title,
    useDebounceFn((newValue) => {
      filters.value.debouncedTitle = newValue.toLowerCase();
    }, 400)
  );

  return filters;
};
