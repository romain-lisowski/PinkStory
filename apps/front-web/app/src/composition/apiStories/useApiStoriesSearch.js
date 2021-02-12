import useFetch from '@/composition/useFetch'

export default async (params = {}) => {
  const queryParams = params
  if (queryParams.categoryIds) {
    queryParams.story_theme_ids = queryParams.categoryIds
    delete queryParams.categoryIds
  }

  const { response, error, fetchData } = useFetch(
    'GET',
    'story/search',
    queryParams
  )

  await fetchData()
  return { response, error }
}
