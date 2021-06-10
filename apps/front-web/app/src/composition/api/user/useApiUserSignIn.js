import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, { email, password }) => {
  const { ok, response, error, isLoading, fetchData } = useFetch(
    'POST',
    'access-token',
    {
      email,
      password,
    }
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { ok, response, error }
}
