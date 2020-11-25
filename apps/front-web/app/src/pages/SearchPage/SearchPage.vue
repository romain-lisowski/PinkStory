<template>
  <div class="mb-10">
    <CategoryBlock>
      <template v-slot:header>
        <div
          class="flex flex-col sm:flex-row justify-between mb-8 pt-8 sm:pt-12"
        >
          <p
            class="block w-full mt-12 sm:mt-0 text-5xl md:text-5xl xl:text-6xl font-semibold text-center"
          >
            {{ $t('search') }}
          </p>
        </div>
      </template>
      <template v-slot:footer>
        <p
          class="block pt-2 sm:pt-4 xl:pt-6 w-full text-lg sm:text-xl xl:text-2xl text-center text-accent"
        >
          {{ results + ' ' + $t('results') }}
        </p>
      </template>
    </CategoryBlock>
    <div class="mx-8">
      <StoryListOrder />
      <StoryList />
    </div>
  </div>
</template>

<script>
import CategoryBlock from '@/components/category/CategoryBlock.vue'
import StoryListOrder from '@/components/story/StoryListOrder.vue'
import StoryList from '@/components/story/StoryList.vue'

export default {
  name: 'SearchPage',
  components: {
    CategoryBlock,
    StoryListOrder,
    StoryList,
  },
  data() {
    return {
      results: 32,
    }
  },
}
</script>

<i18n>
{
  "fr": {
    "search": "Rechercher par catégorie",
    "results": "histoires correspondantes"
  }
}
</i18n>
