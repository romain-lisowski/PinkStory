import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, storyId) => {
  const { ok, response, loading, fetchData } = useFetch(
    'GET',
    `story/${storyId}`
  )

  useLoadingOverlay(store, loading)
  await fetchData()
  return { ok, response }
}
