export default {
  getSearchCategoryIds: ({ state }) => {
    return state.searchCategoryIds
  },
  refreshingSearchCategory: ({ state }) => {
    return state.refreshSearchCategory
  },
  getSearchOrder: ({ state }) => {
    return state.searchOrder
  },
  showingLoadingOverlay: (state) => {
    return state.showLoadingOverlay
  },
}
