import clientApi from './clientApi'

export default {
  async search(params = {}) {
    const queryParams = params

    if (queryParams.categoryIds) {
      queryParams.story_theme_ids = queryParams.categoryIds
      delete queryParams.categoryIds
    }

    return clientApi.fetch('GET', 'story/search', queryParams)
  },

  async get(storyId) {
    return clientApi.fetch('GET', `story/${storyId}`)
  },
}
