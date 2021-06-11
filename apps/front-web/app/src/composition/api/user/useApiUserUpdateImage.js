import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, { jwt, image }) => {
  const { ok, loading, fetchData } = useFetch(
    'PATCH',
    'account/update-image',
    { image },
    jwt
  )

  useLoadingOverlay(store, loading)
  await fetchData()
  return { ok }
}
