import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store) => {
  const { ok, response, loading, fetchData } = useFetch(
    'GET',
    'story-theme/search'
  )

  useLoadingOverlay(store, loading)
  await fetchData()
  return { ok, response }
}
