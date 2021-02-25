let isAdult = null
try {
  isAdult = JSON.parse(localStorage.getItem('isAdult'))
} catch (e) {
  localStorage.removeItem('isAdult')
}

let theme = null
try {
  theme = JSON.parse(localStorage.getItem('theme'))
} catch (e) {
  localStorage.removeItem('theme')
}

export default {
  state: {
    isAdult,
    theme,
    refreshSearchCategory: false,
    searchCategoryIds: [],
    searchOrder: '',
    showLoadingOverlay: false,
  },
}
