import clientApi from './clientApi'

export default {
  async search() {
    return clientApi.fetch('GET', 'story-theme/search')
  },
}
