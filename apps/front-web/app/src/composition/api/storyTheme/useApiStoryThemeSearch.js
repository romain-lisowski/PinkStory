import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async () => {
  const { response, error, isLoading, fetchData } = useFetch(
    'GET',
    'story-theme/search'
  )

  useLoadingOverlay(isLoading)
  await fetchData()
  return { response, error }
}
