<template>
  <div class="flex flex-wrap text-left mt-4 sm:mt-6 xl:mt-8 -mx-6 pt-2 pl-2">
    <StoryListItem
      v-for="(story, index) in stories"
      :key="index"
      :story="story"
    />
  </div>
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
          abstract: `Je m'appelle Nathalie, j'ai quarante-trois ans.
          Je suis mère de famille, d'un fils unique, Daniel, âgé de dix-neuf ans.
          Mon fils Daniel avait pour meilleur ami un garçon de son âge appelé Thomas.
          Tous deux étaient inséparables, de vrais larrons en foire.
          Je connaissais Thomas depuis qu'il avait dix ou onze ans.
          Toujours ensembles, Thomas et Dan partageaient leurs week-ends ensembles.
          Dormant alternativement chez les parents de l'un et chez les parents de l'autre,
          Thomas finit par devenir au fil des ans comme le second enfant de la famille.`,
        },
        story2: {
          author: 'Gypsie',
          gender: 'male',
          title: 'La meilleure amie de ma femme',
          categories: 'Fantasme, Fellation et 2 autres',
          rating: 3.5,
          nbComments: 432,
          imagePath: '7.png',
          abstract: `Cela fait déjà quelques années que ma femme Karen travaille comme assistante
            dans la même boîte avec sa collègue Annie sur la capitale.
            Ma femme est une belle blonde aux yeux verts de quarante ans d’un mètre soixante-cinq pour soixante kilos
            et possède des formes que les hommes aiment, c’est-à-dire un 95C bien ferme et un cul bien rond et musclé après
            plus d’une vingtaine d’années de la pratique de l’équitation. « elle aime monter » comme elle répète à le dire !`,
        },
        story3: {
          author: 'Annizette',
          gender: 'female',
          title: 'L\'initiation',
          categories: 'Soumission, Hard et 5 autres',
          rating: 4,
          nbComments: 2304,
          imagePath: '5.jpg',
          abstract: `L’initiation. Quel ne fut pas mon étonnement lorsque Flora une vague petite cousine par alliance
           me prit à part lors d’un dîner de famille, elle m’entraîna dans le jardin boisé loin de la fête,
           elle semblait vouloir me dire quelque chose.`,
        },
        story4: {
          author: 'Sammy592',
          gender: 'male',
          title: 'Ma femme timide, baisée',
          categories: 'Candaulisme, Sodomie, Réel',
          rating: 4.5,
          nbComments: 5659,
          imagePath: '2.png',
          abstract: `Ma femme Nathaly est plutôt jolie. Pas un visage top modèle, mais un corps à faire tourner les têtes de bien des mecs.
          Nous avons un terrain en campagne avec une petite roulotte et nous allons souvent passer le week-end.
          Mon voisin Jean, un homme dans la trentaine, aux allures rocker, cheveux longs, trouve ma femme superbe et m’a avouer bander
          quand il la voit.`,
        },
      },
    }
  },
}
</script>
