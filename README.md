# Listings App

## Setup

```bash
npm i && npm run dev
```

Runs on localhost:3000

## Stack

- Nuxt (Vue + Typescript)
- Tailwind CSS
- PHP Backend w/ MySQL

## Technical Challenges & Solutions

### Data Layer

- **Problem**: Caching
- **Solution**: Implemented useAsyncData pattern
  ```ts
  const { data: listings } = useAsyncData("listings", () =>
    service.fetchItems()
  );
  ```
- **Result**: Better caching

### State Managment

- **Issue**: Filter state inconsistent, Pagination and filters not consistent
- **Solution**: Global filter state, computed filtering

  ```ts
  // Global filter state
  const filters = useState('filters', () => ({...}))

  // Filter with computed
  const filtered = computed(() =>
    listings.value?.filter(...)
  )
  ```

### Component Structure

- **Problem**: Repeated dialog logic
- **Solution**: Reusable component (didnt use on multiple pages)
  ```vue
  <DeleteDialog v-model="showDialog" @confirm="handleDelete" />
  ```

### UX Improvements

- Loading states
- Flash messages via URL params
- Debounced search
- Pagination after filters

### Performance

- Client-side pagination/filtering
- Computed properties
- Minimal transitions

## Limitations

- Client side pagination/filtering
- No image CDN
- Basic error handling
- Missing tests
