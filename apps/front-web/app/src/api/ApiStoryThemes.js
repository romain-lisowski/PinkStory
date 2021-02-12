import fetchData from '@/composition/useFetch'

export default {
  async search() {
    return fetchData('GET', 'story-theme/search')
  },
}
