import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, storyId) => {
  const { response, error, isLoading, fetchData } = useFetch(
    'GET',
    `story/${storyId}`
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { response, error }
}
