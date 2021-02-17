import fetchData from '@/composition/api/useFetch'

export default {
  async search() {
    return fetchData('GET', 'language/search')
  },
}
