export default {
  isAdult({ commit }) {
    commit('IS_ADULT')
    localStorage.setItem('isAdult', true)
  },
  updateTheme({ commit }, { theme }) {
    commit('SET_THEME', theme)
    localStorage.setItem('theme', JSON.stringify(theme))
  },
  toggleSearchCategory({ state, commit }, { categoryId }) {
    if (!state.state.searchCategoryIds.includes(categoryId)) {
      commit('ADD_SEARCH_CATEGORY', categoryId)
    } else {
      commit('REMOVE_SEARCH_CATEGORY', categoryId)
    }
  },
  updateSearchOrder({ commit }, { searchOrder }) {
    commit('SET_SEARCH_ORDER', searchOrder)
  },
  showLoadingOverlay({ commit }) {
    commit('SHOW_LOADING_OVERLAY')
  },
  hideLoadingOverlay({ commit }) {
    commit('HIDE_LOADING_OVERLAY')
  },
}
