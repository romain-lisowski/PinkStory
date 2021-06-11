import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, { name, gender, email, password }) => {
  const { ok, error, loading, fetchData } = useFetch('POST', 'account/signup', {
    gender,
    name,
    email,
    password,
  })

  useLoadingOverlay(store, loading)
  await fetchData()
  return { ok, error }
}
