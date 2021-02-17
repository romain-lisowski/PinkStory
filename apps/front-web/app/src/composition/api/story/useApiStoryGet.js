import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (storyId) => {
  const { response, error, isLoading, fetchData } = useFetch(
    'GET',
    `story/${storyId}`
  )

  useLoadingOverlay(isLoading)
  await fetchData()
  return { response, error }
}
