import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, { jwt, passwordOld, passwordNew }) => {
  const { ok, loading, fetchData } = useFetch(
    'PATCH',
    'account/update-password',
    {
      old_password: passwordOld,
      password: passwordNew,
    },
    jwt
  )

  useLoadingOverlay(store, loading)
  await fetchData()
  return { ok }
}
