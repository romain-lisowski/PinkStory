<template>
  <div class="flex flex-col justify-center items-center">
    <div class="flex flex-col p-12 bg-primary-inverse bg-opacity-5 rounded-xl">
      <p class="text-8xl font-bold text-accent">{{ t('error-404') }}</p>
      <p class="mt-6 text-xl">{{ t('not-found') }}</p>
      <router-link
        :to="{ name: 'Home' }"
        class="mt-8 p-4 bg-accent bg-opacity-100 rounded-lg"
        >{{ t('go-to-home') }}</router-link
      >
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFoundPage',
}
</script>

<!-- <i18n>
{
  "fr": {
    "error-404": "404",
    "not-found": "L'histoire que vous recherchez n'existe pas.",
    "go-to-home": "Découvrir de nouvelles histoires"
  }
}
</i18n> -->
