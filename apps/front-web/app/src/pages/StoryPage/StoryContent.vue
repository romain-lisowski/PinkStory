<template v-if="story">
  <div class="w-5/6 lg:w-3/4 pt-12">
    <p
      class="text-justify font-thin text-base sm:text-lg lg:text-xl tracking-wide leading-relaxed"
      v-html="story.content"
    ></p>

    <span class="text-base sm:text-lg lg:text-xl font-bold"
      >{{ t('categories') }} :
      <span
        class="text-base sm:text-lg lg:text-xl font-normal text-accent-highlight"
        >{{ story.story_themes.map((theme) => theme.title).join(', ') }}</span
      >
    </span>

    <div class="flex justify-between w-full my-12">
      <div class="flex items-center">
        <router-link
          v-if="story.previous"
          :to="{ name: 'Story', params: { storyId: story.previous.id } }"
        >
          <ui-font-awesome-icon
            icon="chevron-left"
            class="h-8 text-2xl mr-1 md:mr-4"
          />
          <span class="flex flex-col">
            <span class="text-base sm:text-lg lg:text-xl text-left">{{
              t('previousChapter')
            }}</span>
            <span class="text-sm md:text-base font-bold text-left">
              {{ story.previous.title }}
            </span>
          </span>
        </router-link>
      </div>
      <div class="flex items-center">
        <router-link
          v-if="story.next"
          :to="{ name: 'Story', params: { storyId: story.next.id } }"
        >
          <span class="flex flex-col">
            <span class="text-base sm:text-lg lg:text-xl text-right">{{
              t('nextChapter')
            }}</span>
            <span class="text-sm md:text-base font-bold text-right">
              {{ story.next.title }}
            </span>
          </span>
          <ui-font-awesome-icon
            icon="chevron-right"
            class="h-8 text-2xl ml-1 md:ml-2"
          />
        </router-link>
      </div>
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
          categories: 'Catégories',
          previousChapter: 'Chapitre précédent',
          nextChapter: 'Chapitre suivant',
        },
      },
    })

    return { t }
  },
}
</script>
