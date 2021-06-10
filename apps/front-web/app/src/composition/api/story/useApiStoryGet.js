import useFetch from '@/composition/api/useFetch'
import useLoadingOverlay from '@/composition/api/useLoadingOverlay'

export default async (store, storyId) => {
  const { ok, response, isLoading, fetchData } = useFetch(
    'GET',
    `story/${storyId}`
  )

  useLoadingOverlay(store, isLoading)
  await fetchData()
  return { ok, response }
}
