import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, queryParams = {}, withLoadingOverlay = true) => {
  const { ok, response, loading, fetchData } = useFetch(
    'GET',
    'story/search',
    queryParams
  )

  if (withLoadingOverlay) {
    useLoadingOverlay(store, loading)
  }

  await fetchData()
  return { ok, response }
}
