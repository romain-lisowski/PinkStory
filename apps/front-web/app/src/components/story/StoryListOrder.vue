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
export default {
  name: 'StoryListOrder',
  data() {
    return {
      activeOrder: this.$store.state.storyOrder,
      activeClasses: ['text-primary', 'bg-accent'],
      inactiveClasses: ['border'],
    }
  },
  methods: {
    orderRate() {
      this.changeOrder('rate')
    },
    orderDate() {
      this.changeOrder('date')
    },
    orderRandom() {
      this.changeOrder('random')
    },
    changeOrder(storyOrder) {
      this.activeOrder = storyOrder
      this.$store.dispatch('updateStoryOrder', { storyOrder })
    },
  },
}
</script>

<!-- <i18n>
{
  "fr": {
    "filter-by": "Filtrer par",
    "rate": "Note",
    "date": "Date",
    "random": "Aléatoire"
  }
}
</i18n> -->
