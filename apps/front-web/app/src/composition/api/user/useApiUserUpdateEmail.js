import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, { jwt, email }) => {
  const { ok, loading, fetchData } = useFetch(
    'PATCH',
    'account/update-email',
    {
      email,
    },
    jwt
  )

  useLoadingOverlay(store, loading)
  await fetchData()
  return { ok }
}
