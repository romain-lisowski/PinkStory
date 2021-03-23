import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'
import useApiLanguageSearch from '@/composition/api/language/useApiLanguageSearch'

export default async (store, { jwt, name }) => {
  const { languages } = await useApiLanguageSearch(store)
  const { response, error, isLoading, fetchData } = useFetch(
    'PATCH',
    'account/update-information',
    null,
    {
      name,
      language_id: languages[1].id,
    },
    jwt
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { response, error }
}
