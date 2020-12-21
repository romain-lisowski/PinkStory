<template>
  <div class="relative h-128 sm:h-256 lg:h-screen">
    <div
      class="absolute w-full h-128 sm:h-256 lg:h-screen bg-center bg-cover"
      :style="{
        '-webkit-filter': 'blur(8px)',
        'background-image': `url(${require('@/assets/images/1.jpg')})`,
      }"
    ></div>
    <div
      class="absolute pt-32 lg:pt-48 w-full h-128 sm:h-256 lg:h-screen bg-primary bg-opacity-50"
    >
      <img
        class="mx-auto w-1/3 sm:w-1/3 lg:w-1/4 object-cover rounded-2xl"
        :src="require(`@/assets/images/${story.imagePath}`)"
      />
      <h2
        class="mt-8 lg:mt-20 text-4xl sm:text-5xl md:text-6xl lg:text-6xl xl:text-7xl font-bold text-primary tracking-tighter leading-none"
      >
        {{ story.parentTitle }}
      </h2>
      <h1
        class="mt-2 sm:mt-0 text-xl sm:text-3xl md:text-3xl lg:text-3xl xl:text-4xl text-primary"
      >
        {{ story.title }}
      </h1>

      <p
        class="inline-block mt-6 lg:mt-20 px-3 py-1 text-sm sm:text-lg md:text-2xl lg:text-xl xl:text-2xl text-primary bg-opacity-100 border-2 hover:bg-accent rounded-l-full rounded-r-full cursor-pointer transition-all duration-300 ease-in"
      >
        <font-awesome-icon
          icon="heart"
          class="mr-2 -mt-1 w-4 sm:w-6 inline-block"
        />
        <span class="inline-block">{{ t('add-to-favorites') }}</span>
      </p>
    </div>
  </div>
</template>

<script>
import { useI18n } from 'vue-i18n'

export default {
  props: {
    story: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'add-to-favorites': 'Ajouter aux favoris',
        },
      },
    })

    return { t }
  },
}
</script>
