import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, { jwt, email }) => {
  const { response, error, isLoading, fetchData } = useFetch(
    'PATCH',
    'account/update-email',
    null,
    {
      'email[first]': email,
      'email[second]': email,
    },
    jwt
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { response, error }
}
