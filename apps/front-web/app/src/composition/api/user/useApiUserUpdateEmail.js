import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, { jwt, newEmail }) => {
  const { ok, isLoading, fetchData } = useFetch(
    'PATCH',
    'account/update-email',
    {
      'email[first]': newEmail,
      'email[second]': newEmail,
    },
    jwt
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { ok }
}
