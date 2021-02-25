import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, email, password) => {
  const { response, error, isLoading, fetchData } = useFetch(
    'GET',
    'account/login',
    null,
    { email, password }
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { response, error }
}
