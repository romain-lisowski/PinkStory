import useFetch from '@/composition/useFetch'
import useLoadingOverlay from './useLoadingOverlay'

export default async (storyId) => {
  const { response, error, isLoading, fetchData } = useFetch(
    'GET',
    `story/${storyId}`
  )

  useLoadingOverlay(isLoading)
  await fetchData()
  return { response, error }
}
