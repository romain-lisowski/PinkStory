import clientApi from './clientApi'

export default {
  async search(params = {}) {
    const searchParams = new URLSearchParams(params)

    if (params.categoryIds) {
      params.categoryIds.forEach((categoryId) => {
        searchParams.append('story_theme_ids[]', categoryId)
      })
      searchParams.delete('categoryIds')
    }

    return clientApi.fetch('story/search', searchParams)
  },

  async get(storyId) {
    return clientApi.fetch(`story/${storyId}`)
  },
}
