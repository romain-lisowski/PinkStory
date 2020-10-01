<template>
  <div>
    <StoryMain :story="mainStory" />
    <StoryTopRated />
    <StoryLatest />
    <Category />
  </div>
</template>

<script>

import StoryMain from '@/components/story/StoryMain.vue'
import StoryTopRated from '@/components/story/StoryTopRated.vue'
import StoryLatest from '@/components/story/StoryLatest.vue'
import Category from '@/components/category/Category.vue'

export default {
  name: 'Home',
  components: {
    StoryMain,
    StoryTopRated,
    StoryLatest,
    Category,
  },
  data() {
    return {
      mainStory: {
        author: 'Estelle48',
        gender: 'female',
        date: '26/09/2020',
        title: 'Chantage et soumission',
        categories: 'Hétéro, Triolisme, Hard et 4 autres',
        rating: 4.5,
        abstract: `Cette histoire s'est passée l'an dernier et dure depuis.
        Je me présente Estelle, 48 ans, mariée avec deux grandes filles de 26 et 23 ans.
        Mon mari Marc et moi vivons dans un petit lotissement en région parisienne.
        Ma grande fille Cathy est marié depuis deux ans avec Laurent,
        bel homme qu'elle a rencontré sur les bancs de la fac.`,
      },
    }
  },
}
</script>
