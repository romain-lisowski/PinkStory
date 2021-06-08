import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, { jwt, image }) => {
  const { response, error, isLoading, fetchData } = useFetch(
    'PATCH',
    'account/update-image',
    { image },
    jwt
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { response, error }
}
