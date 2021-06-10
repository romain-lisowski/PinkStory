import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, { name, gender, email, password }) => {
  const {
    ok,
    response,
    error,
    formViolations,
    isLoading,
    fetchData,
  } = useFetch('POST', 'account/signup', {
    gender,
    name,
    email,
    password,
  })

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { ok, response, error, formViolations }
}
