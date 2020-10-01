<template>
  <div
    class="bg-gradient-to-r bg-no-repeat bg-color-white -mt-32 mb-20"
    :style="{
      'background-position-x': '35rem',
      'background-size': '70%',
      'background-image':
        `radial-gradient(
          70% 50% at 70% 40%,
          rgba(255, 255, 255, 0) 25%,
          rgba(255, 255, 255, 1) 100%),
        url(${require('@/assets/images/1.jpg')})`
    }"
  >
    <div class="mx-12 pt-48">
      <h1 class="text-left text-psblack font-extrabold leading-none text-9xl w-3/5 font-psbold">
        {{ story.title }}
      </h1>
      <div class="text-left text-psblack font-extrabold text-4xl">
        18+
        <span class="font-normal mx-2">|</span>
        {{ story.date }}
        <span class="font-normal mx-2">|</span>
        {{ story.author }}
        <span
          v-if="story.gender === 'female'"
          class="font-medium"
        >&#9792;</span><span
          v-else
          class="font-medium"
        >&#9794;</span>
      </div>
      <div class="text-left text-psred-lighter text-4xl tracking-wide">
        {{ story.categories }}
      </div>
      <div class="text-left mt-10 text-2xl tracking-wide text-psblack w-2/3 leading-10">
        {{ story.abstract }}
      </div>

      <button class="block mt-8 mx-0">
        {{ $t('read-now') }}
      </button>
    </div>
  </div>
</template>

<script>

export default {
  name: 'StoryMain',
  props: {
    story: {
      type: Object,
      required: true,
    },
  },
}
</script>

<i18n>
{
  "fr": {
    "read-now": "Lire maintenant"
  }
}
</i18n>
