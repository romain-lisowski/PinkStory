import useFetch from '@/composition/useFetch'
import useLoadingOverlay from './useLoadingOverlay'

export default async (params = {}) => {
  const queryParams = params
  if (queryParams.categoryIds) {
    queryParams.story_theme_ids = queryParams.categoryIds
    delete queryParams.categoryIds
  }

  const { response, error, isLoading, fetchData } = useFetch(
    'GET',
    'story/search',
    queryParams
  )

  useLoadingOverlay(isLoading)
  await fetchData()
  return { response, error }
}
