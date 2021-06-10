import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store) => {
  const { ok, response, isLoading, fetchData } = useFetch(
    'GET',
    'story-theme/search'
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { ok, response }
}
