import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, { jwt }) => {
  const { ok, isLoading, fetchData } = useFetch(
    'DELETE',
    'account/delete-image',
    null,
    jwt
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { ok }
}
