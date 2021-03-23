import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, { jwt }) => {
  const { response, error, isLoading, fetchData } = useFetch(
    'DELETE',
    'account/delete-image',
    null,
    null,
    jwt
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { response, error }
}
