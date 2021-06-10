import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, jwt) => {
  const { ok, response, isLoading, fetchData } = useFetch(
    'GET',
    'account',
    null,
    jwt
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { ok, response }
}
