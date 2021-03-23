import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, { jwt, newEmail }) => {
  const { response, error, isLoading, fetchData } = useFetch(
    'PATCH',
    'account/update-email',
    null,
    {
      'email[first]': newEmail,
      'email[second]': newEmail,
    },
    jwt
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { response, error }
}
