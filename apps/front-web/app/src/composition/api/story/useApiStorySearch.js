import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, params = {}, withLoadingOverlay = true) => {
  const queryParams = params
  if (queryParams.categoryIds) {
    queryParams.story_theme_ids = queryParams.categoryIds
    delete queryParams.categoryIds
  }

  const { response, error, isLoading, fetchData } = useFetch(
    'GET',
    'story/search'
  )

  if (withLoadingOverlay) {
    useLoadingOverlay(store, isLoading)
  }

  await fetchData()
  return { response, error }
}
