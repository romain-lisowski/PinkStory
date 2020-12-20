<template>
  <div class="mt-10 text-right">
    {{ t('filter-by') + ' : ' }}
    <button
      class="ml-4 px-4 py-3 rounded-lg transition-all duration-300 ease-in"
      :class="activeOrder === 'rate' ? activeClasses : inactiveClasses"
      @click="orderRate"
    >
      {{ t('rate') }}
    </button>
    <button
      class="ml-4 px-4 py-3 rounded-lg transition-all duration-300 ease-in"
      :class="activeOrder === 'date' ? activeClasses : inactiveClasses"
      @click="orderDate"
    >
      {{ t('date') }}
    </button>
    <button
      class="ml-4 px-4 py-3 rounded-lg transition-all duration-300 ease-in"
      :class="activeOrder === 'random' ? activeClasses : inactiveClasses"
      @click="orderRandom"
    >
      {{ t('random') }}
    </button>
  </div>
</template>

<script>
import { reactive } from 'vue'
import { useStore } from 'vuex'
import { useI18n } from 'vue-i18n'

export default {
  setup() {
    const store = useStore()
    const data = reactive({
      activeOrder: store.state.storyOrder,
      activeClasses: ['text-primary', 'bg-accent'],
      inactiveClasses: ['border'],
    })

    const changeOrder = (storyOrder) => {
      data.activeOrder = storyOrder
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

    return { ...data, orderRate, orderDate, orderRandom, changeOrder, t }
  },
}
</script>
