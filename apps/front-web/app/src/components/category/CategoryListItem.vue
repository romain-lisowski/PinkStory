<template>
  <span
    :class="active ? activeClasses : ''"
    class="rounded-xl py-2 px-4 sm:py-3 sm:px-6 border bg-white text-sm sm:text-md xl:text-xl text-gray-600 bg-opacity-100 cursor-pointer transition-all duration-300 ease-in"
    @click="toggleActive"
  >
    {{ category.title }}
  </span>
</template>

<script>
import { ref } from 'vue'
import { useStore } from 'vuex'

export default {
  props: {
    category: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const store = useStore()
    const active = ref(
      store.state.site.state.searchCategoryIds.includes(props.category.id)
    )
    const activeClasses = ['bg-accent', 'text-primary-inverse']

    const toggleActive = () => {
      active.value = !active.value
      store.dispatch('site/toggleSearchCategory', {
        categoryId: props.category.id,
      })
    }

    return { active, activeClasses, toggleActive }
  },
}
</script>
