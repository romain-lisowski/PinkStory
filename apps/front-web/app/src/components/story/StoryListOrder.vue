<template>
  <div class="mt-10 text-right">
    {{ t('filter-by') + ' : ' }}
    <button
      class="ml-4 px-4 py-3 border rounded-lg transition-all duration-300 ease-in"
      :class="activeOrder === 'rate' ? activeClasses : ''"
      @click="orderRate"
    >
      {{ t('rate') }}
    </button>
    <button
      class="ml-4 px-4 py-3 border rounded-lg transition-all duration-300 ease-in"
      :class="activeOrder === 'date' ? activeClasses : ''"
      @click="orderDate"
    >
      {{ t('date') }}
    </button>
    <button
      class="ml-4 px-4 py-3 border rounded-lg transition-all duration-300 ease-in"
      :class="activeOrder === 'random' ? activeClasses : ''"
      @click="orderRandom"
    >
      {{ t('random') }}
    </button>
  </div>
</template>

<script>
import { useStore } from 'vuex'
import { useI18n } from 'vue-i18n'
import { ref } from 'vue'

export default {
  setup() {
    const store = useStore()
    const activeOrder = ref(store.state.storyOrder)
    const activeClasses = ['text-primary', 'bg-accent', 'border-accent']

    const changeOrder = (storyOrder) => {
      activeOrder.value = storyOrder
      store.dispatch('updateStoryOrder', { storyOrder })
    }

    const orderRate = () => {
      changeOrder('rate')
    }
    const orderDate = () => {
      changeOrder('date')
    }
    const orderRandom = () => {
      changeOrder('random')
    }

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'filter-by': 'Filtrer par',
          rate: 'Note',
          date: 'Date',
          random: 'Aléatoire',
        },
      },
    })

    return {
      activeOrder,
      activeClasses,
      orderRate,
      orderDate,
      orderRandom,
      changeOrder,
      t,
    }
  },
}
</script>
