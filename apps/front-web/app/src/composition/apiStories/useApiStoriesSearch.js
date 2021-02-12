import { ref } from 'vue'
import useFetch from '@/composition/useFetch'

export default async (params = {}) => {
  let list = ref(null)
  let listError = ref(null)

  const queryParams = params
  if (queryParams.categoryIds) {
    queryParams.story_theme_ids = queryParams.categoryIds
    delete queryParams.categoryIds
  }

  const { response, error, fetchData } = await useFetch(
    'GET',
    'story/search',
    queryParams
  )

  await fetchData()
  list = response.value
  listError = error.value

  return { list, listError }
}
