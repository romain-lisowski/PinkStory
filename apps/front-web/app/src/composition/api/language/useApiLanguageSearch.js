import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store) => {
  const { response, error, loading, fetchData } = useFetch(
    'GET',
    'language/search'
  )

  useLoadingOverlay(store, loading)
  await fetchData()
  return { response, error }
}
