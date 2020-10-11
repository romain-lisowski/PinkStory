<template>
  <div
    class="flex flex-col justify-center items-center bg-cover"
    :style="{
      'background-image': `url(${require('@/assets/images/book.jpg')})`,
    }"
  >
    <div class="flex flex-col p-12 bg-primary bg-opacity-75 rounded-xl">
      <p class="text-8xl font-bold text-accent">{{ $t('error-404') }}</p>
      <p class="mt-6 text-xl">{{ $t('not-found') }}</p>
      <router-link
        :to="{ name: 'Home' }"
        class="mt-8 p-4 bg-accent rounded-lg"
        >{{ $t('go-to-home') }}</router-link
      >
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFound',
}
</script>

<i18n>
{
  "fr": {
    "error-404": "404",
    "not-found": "L'histoire que vous recherchez n'existe pas.",
    "go-to-home": "Découvrir de nouvelles histoires"
  }
}
</i18n>
