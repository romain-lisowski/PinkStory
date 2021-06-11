import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, params = {}, withLoadingOverlay = true) => {
  const queryParams = params
  if (queryParams.categoryIds) {
    queryParams.story_theme_ids = queryParams.categoryIds
    delete queryParams.categoryIds
  }

  const { ok, response, loading, fetchData } = useFetch('GET', 'story/search')

  if (withLoadingOverlay) {
    useLoadingOverlay(store, loading)
  }

  await fetchData()
  return { ok, response }
}
