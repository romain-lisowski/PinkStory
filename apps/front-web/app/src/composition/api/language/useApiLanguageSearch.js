import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store) => {
  const { response, error, isLoading, fetchData } = useFetch(
    'GET',
    'language/search'
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { response, error }
}
