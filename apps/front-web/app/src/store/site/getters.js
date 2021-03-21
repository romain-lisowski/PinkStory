export default {
  showingLoadingOverlay: (state) => {
    return state.showLoadingOverlay
  },
  getSearchOrder: ({ state }) => {
    return state.searchOrder
  },
  getSearchCategoryIds: ({ state }) => {
    return state.searchCategoryIds
  },
  refreshingSearchCategory: ({ state }) => {
    return state.refreshSearchCategory
  },
}
