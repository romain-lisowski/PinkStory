import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'
import useApiLanguageSearch from '@/composition/api/language/useApiLanguageSearch'

export default async (store, { jwt, name }) => {
  const { languages } = await useApiLanguageSearch(store)
  const { ok, loading, fetchData } = useFetch(
    'PATCH',
    'account/update-information',
    {
      name,
      language_id: languages[1].id,
    },
    jwt
  )

  useLoadingOverlay(store, loading)
  await fetchData()
  return { ok }
}
