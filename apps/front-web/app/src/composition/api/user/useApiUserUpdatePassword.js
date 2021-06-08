import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (
  store,
  { jwt, passwordOld, passwordNew, passwordNewConfirm }
) => {
  const { response, error, isLoading, fetchData } = useFetch(
    'PATCH',
    'account/update-password',
    {
      old_password: passwordOld,
      'password[first]': passwordNew,
      'password[second]': passwordNewConfirm,
    },
    jwt
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { response, error }
}
