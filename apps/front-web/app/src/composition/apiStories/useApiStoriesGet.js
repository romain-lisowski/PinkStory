import { toRefs, reactive } from 'vue'
import useFetch from '@/composition/useFetch'

export default (storyId) => {
  const data = reactive({ response: null, error: null, fetching: false })

  const submitted = async () => {
    const { response, error, fetchData } = useFetch('GET', `story/${storyId}`)

    fetchData()
    data.response = response
    data.error = error
  }

  return { submitted, ...toRefs(data) }
}
