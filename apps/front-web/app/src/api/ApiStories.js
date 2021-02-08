import clientApi from './clientApi'

export default {
  async search(jwt, params = {}) {
    const searchParams = new URLSearchParams(params)

    if (params.categoryIds) {
      params.categoryIds.forEach((categoryId) => {
        searchParams.append('story_theme_ids[]', categoryId)
      })
      searchParams.delete('categoryIds')
    }

    return clientApi.fetch('story/search', searchParams)
  },

  async get(jwt, storyId) {
    const uri = new URL(`story/${storyId}`)

    return clientApi.fetch(uri)
  },
}
