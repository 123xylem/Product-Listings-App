## Problem Solving Journey & Solutions

### Data Fetching & Caching

- **Issue**: Initial implementation had redundant data fetching logic
- **Solution**: Implemented `useAsyncData` for efficient caching and data management
- **Trade-off**: Chose client-side pagination over API pagination for simplicity and reduced server load
- **Scalability**: Cache invalidation strategy in place for CRUD operations

### State Management

- **Issue**: Filters weren't persisting and affecting pagination incorrectly
- **Solution**:
  - Created global filter state using `useState`
  - Implemented filter-then-paginate logic
  - Added page reset on filter changes
- **Trade-off**: Global state vs prop drilling

### API Response Handling

- **Issue**: Single listing fetch returning 404 on direct URL access
- **Solution**: Improved error handling and response type checking
- **Scalability**: Added proper TypeScript interfaces for API responses

### UI/UX Improvements

- **Issue**: No feedback for successful operations
- **Solution**: Added flash messages using URL query params
- **Trade-off**: Chose URL params over state management for simplicity

### Component Design

- **Issue**: Delete dialog logic duplicated across pages
- **Solution**: Created reusable `DeleteDialog` component
- **Scalability**: Modular design for easy maintenance

### Form Handling

- **Issue**: Form values not displaying correctly (case sensitivity)
- **Solution**: Standardized case handling for status values
- **Trade-off**: Frontend validation vs backend normalization

### Performance Optimizations

- **Issue**: Unnecessary re-renders with filter changes
- **Solution**:
  - Implemented debounced search
  - Added computed properties for filtered results
- **Scalability**: Ready for larger datasets with pagination

### Visual Feedback

- **Issue**: Lack of loading states and transitions
- **Solution**: Added loading spinners and smooth transitions
- **Trade-off**: Enhanced UX vs slight performance overhead

## Future Considerations

### Potential Improvements

- Implement server-side pagination for larger datasets
- Add caching headers for better performance
- Implement optimistic updates for better UX
- Add end-to-end testing
- Consider implementing real-time updates

### Scalability Notes

- Current client-side filtering works well for small to medium datasets
- Consider implementing backend search for large datasets
- Image handling should be moved to CDN for production
- Add rate limiting for API endpoints
- Consider implementing service worker for offline capability
