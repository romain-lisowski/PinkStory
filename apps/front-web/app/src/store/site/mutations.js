export default {
  IS_ADULT(state) {
    state.isAdult = true
  },
  SET_THEME(state, theme) {
    state.theme = theme
  },
  ADD_SEARCH_CATEGORY(context, categoryId) {
    context.state.searchCategoryIds.push(categoryId)
  },
  REMOVE_SEARCH_CATEGORY(state, categoryId) {
    const index = state.state.searchCategoryIds.indexOf(categoryId)
    if (index > -1) {
      state.state.searchCategoryIds.splice(index, 1)
    }
  },
  REFRESH_SEARCH_CATEGORY(context) {
    // eslint-disable-next-line no-param-reassign
    context.state.refreshSearchCategory = !context.state.refreshSearchCategory
  },
  SET_SEARCH_ORDER({ state }, searchOrder) {
    state.searchOrder = searchOrder
  },
  SHOW_LOADING_OVERLAY(state) {
    state.showLoadingOverlay = true
  },
  HIDE_LOADING_OVERLAY(state) {
    state.showLoadingOverlay = false
  },
}
