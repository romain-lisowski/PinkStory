import fetchData from '@/composition/useFetch'

export default {
  async search() {
    return fetchData('GET', 'language/search')
  },
}
