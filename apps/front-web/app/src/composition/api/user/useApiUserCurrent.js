import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, jwt) => {
  const { ok, response, loading, fetchData } = useFetch(
    'GET',
    'account',
    null,
    jwt
  )

  useLoadingOverlay(store, loading)
  await fetchData()
  return { ok, response }
}
