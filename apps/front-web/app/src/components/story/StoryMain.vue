<template>
  <div
    class="bg-gradient-to-r bg-no-repeat bg-color-white -mt-32"
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
        Chantage et soumission
      </h1>
      <div class="text-left text-psblack font-extrabold text-4xl">
        18+
        <span class="font-normal mx-2">|</span>
        26/09/2020
        <span class="font-normal mx-2">|</span>
        Hélène78
      </div>
      <div class="text-left text-psred-lighter text-4xl tracking-wide">
        Hétéro, Triolisme, Hard et 4 autres
      </div>
      <div class="text-left mt-10 text-2xl tracking-wide text-psblack w-2/3 leading-10">
        Cette histoire s'est passée l'an dernier et dure depuis.
        Je me présente Estelle, 48 ans, mariée avec deux grandes filles de 26 et 23 ans.
        Mon mari Marc et moi vivons dans un petit lotissement en région parisienne.
        Ma grande fille Cathy est marié depuis deux ans avec Laurent, bel homme qu'elle a rencontré sur les bancs de la fac.
      </div>

      <button class="block mt-8 mx-0">
        Lire maintenant
      </button>
    </div>
  </div>
</template>

<script>

export default {
  name: 'StoryMain',
}
</script>
