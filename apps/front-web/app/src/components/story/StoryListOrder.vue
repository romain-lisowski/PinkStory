<template>
  <div class="mt-10 flex justify-between">
    <p class="w-full text-lg sm:text-xl xl:text-2xl text-left text-accent">
      {{ nbResults + ' ' + t('results') }}
    </p>
    <span class="w-full text-right">
      {{ t('filter-by') + ' : ' }}
      <button
        class="ml-4 px-4 py-3 border rounded-lg transition-all duration-300 ease-in"
        :class="activeOrder === 'ORDER_POPULAR' ? activeClasses : ''"
        @click="orderRate"
      >
        {{ t('rate') }}
      </button>
      <button
        class="ml-4 px-4 py-3 border rounded-lg transition-all duration-300 ease-in"
        :class="activeOrder === 'ORDER_CREATED_AT' ? activeClasses : ''"
        @click="orderDate"
      >
        {{ t('date') }}
      </button>
      <button
        class="ml-4 px-4 py-3 border rounded-lg transition-all duration-300 ease-in"
        :class="activeOrder === null ? activeClasses : ''"
        @click="orderRandom"
      >
        {{ t('random') }}
      </button>
    </span>
  </div>
</template>

<script>
import { useStore } from 'vuex'
import { useI18n } from 'vue-i18n'
import { ref } from 'vue'

export default {
  props: {
    nbResults: {
      type: Number,
      default: 0,
    },
  },
  setup() {
    const store = useStore()
    const activeOrder = ref(store.state.searchOrder)
    const activeClasses = ['text-primary', 'bg-accent', 'border-accent']

    const changeOrder = (searchOrder) => {
      activeOrder.value = searchOrder
      store.dispatch('updateSearchOrder', { searchOrder })
    }

    const orderRate = () => {
      changeOrder('ORDER_POPULAR')
    }

    const orderDate = () => {
      changeOrder('ORDER_CREATED_AT')
    }

    const orderRandom = () => {
      changeOrder(null)
    }

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          results: 'histoires correspondantes',
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
