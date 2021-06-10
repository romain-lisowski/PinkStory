export default {
  isAdult({ commit }) {
    commit('IS_ADULT')
  },
  setTheme({ commit }, { theme }) {
    commit('SET_THEME', theme)
  },
  toggleSearchCategory({ state, dispatch, commit }, categoryId) {
    if (!state.state.searchCategoryIds.includes(categoryId)) {
      commit('ADD_SEARCH_CATEGORY', categoryId)
    } else {
      commit('REMOVE_SEARCH_CATEGORY', categoryId)
    }
    dispatch('refreshSearchCategory')
  },
  refreshSearchCategory({ commit }) {
    commit('REFRESH_SEARCH_CATEGORY')
  },
  updateSearchOrder({ commit }, searchOrder) {
    commit('SET_SEARCH_ORDER', searchOrder)
  },
  showLoadingOverlay({ commit }) {
    commit('SHOW_LOADING_OVERLAY')
  },
  hideLoadingOverlay({ commit }) {
    commit('HIDE_LOADING_OVERLAY')
  },
}
