import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, { name, gender, email, password }) => {
  const { response, error, isLoading, fetchData } = useFetch(
    'POST',
    'account/signup',
    {
      gender,
      name,
      email,
      password,
    }
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { response, error }
}
