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
  emits: ['change-order'],
  setup() {
    const store = useStore()
    const activeOrder = ref(store.state.searchOrder || 'ORDER_POPULAR')
    const activeClasses = ['text-primary', 'bg-accent', 'border-accent']

    const changeOrder = async (searchOrder) => {
      if (activeOrder.value !== searchOrder) {
        activeOrder.value = searchOrder
      }
      await store.dispatch('updateSearchOrder', { searchOrder })
    }

    const orderRate = () => {
      changeOrder('ORDER_POPULAR')
    }

    const orderDate = () => {
      changeOrder('ORDER_CREATED_AT')
    }

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          results: 'histoires correspondantes',
          'filter-by': 'Filtrer par',
          rate: 'Note',
          date: 'Date',
        },
      },
    })

    return {
      activeOrder,
      activeClasses,
      orderRate,
      orderDate,
      changeOrder,
      t,
    }
  },
}
</script>
