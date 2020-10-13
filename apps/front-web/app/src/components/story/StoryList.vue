<template>
  <ul class="flex flex-wrap -mx-6 mt-4 sm:mt-6 xl:mt-8 pt-2 pl-2 text-left">
    <StoryListItem
      v-for="(story, index) in stories"
      :key="index"
      :story="story"
    />
  </ul>
</template>

<script>
import StoryListItem from '@/components/story/StoryListItem.vue'

export default {
  name: 'StoryList',
  components: {
    StoryListItem,
  },
  data() {
    return {
      stories: {
        story1: {
          author: 'Nathalie38',
          gender: 'female',
          title: 'Une autre histoire',
          categories: 'Bisexuel, Soft et 3 autres',
          rating: 4,
          nbComments: 1454,
          imagePath: '4.png',
        },
        story2: {
          author: 'Gypsie',
          gender: 'male',
          title: 'La meilleure amie de ma femme',
          categories: 'Fantasme, Fellation et 2 autres',
          rating: 3.5,
          nbComments: 432,
          imagePath: '7.png',
        },
        story3: {
          author: 'Annizette',
          gender: 'female',
          title: "L'initiation",
          categories: 'Soumission, Hard et 5 autres',
          rating: 4,
          nbComments: 2304,
          imagePath: '5.jpg',
        },
        story4: {
          author: 'Sammy592',
          gender: 'male',
          title: 'Ma femme timide, baisée',
          categories: 'Candaulisme, Sodomie, Réel',
          rating: 4.5,
          nbComments: 5659,
          imagePath: '2.png',
        },
      },
    }
  },
}
</script>
