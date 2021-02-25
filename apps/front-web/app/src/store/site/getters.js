export default {
  showingLoadingOverlay: (state) => {
    return state.showLoadingOverlay
  },
  getSearchOrder: (context) => {
    return context.state.searchOrder
  },
  getSearchCategoryIds: (context) => {
    return context.state.searchCategoryIds
  },
  refreshingSearchCategory: (context) => {
    return context.state.refreshSearchCategory
  },
}
