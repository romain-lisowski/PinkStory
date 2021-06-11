import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, { jwt }) => {
  const { ok, loading, fetchData } = useFetch(
    'DELETE',
    'account/delete-image',
    null,
    jwt
  )

  useLoadingOverlay(store, loading)
  await fetchData()
  return { ok }
}
