<template>
  <div class="flex flex-col justify-center items-center">
    <p class="font-bold text-4xl sm:text-5xl lg:text-5xl">
      {{ t('write') }}
    </p>
    <div class="w-4/5 md:w-2/3 my-10 bg-primary">
      <div class="flex flex-col gap-8 items-center justify-center">
        <div
          class="mb-4 px-10 pt-6 pb-10 bg-primary-inverse bg-opacity-5 rounded-xl w-full"
        >
          <input
            id="title"
            type="text"
            name="title"
            :placeholder="t('title')"
            class="my-5 p-3 w-full rounded-md bg-primary bg-opacity-100"
          />

          <button
            class="mt-6 py-4 text-lg font-light tracking-wide text-primary bg-accent bg-opacity-100 rounded-lg w-full"
            type="submit"
          >
            {{ t('send') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useI18n } from 'vue-i18n'

export default {
  setup() {
    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          write: 'Ecrire une histoire',
          title: "Titre de l'histoire",
          send: 'Envoyer',
        },
      },
    })

    return { t }
  },
}
</script>
