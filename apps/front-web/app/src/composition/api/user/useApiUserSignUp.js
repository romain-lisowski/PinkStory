import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (
  store,
  { pseudo, gender, email, password, passwordConfirm }
) => {
  const { response, error, isLoading, fetchData } = useFetch(
    'POST',
    'account/signup',
    {
      name: pseudo,
      gender,
      email,
      'password[first]': password,
      'password[second]': passwordConfirm,
    }
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { response, error }
}
