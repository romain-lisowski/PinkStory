<template>
  <div class="w-5/6 lg:w-3/4 pt-12">
    <p
      class="text-justify font-thin text-base sm:text-lg lg:text-xl tracking-wide leading-relaxed"
      v-html="story.content"
    ></p>

    <span class="text-base sm:text-lg lg:text-xl font-bold"
      >{{ t('categories') }} :
      <span
        class="text-base sm:text-lg lg:text-xl font-normal text-accent-highlight"
        >{{ data.storyCategories }}</span
      >
    </span>

    <div class="flex justify-between w-full my-12">
      <div class="flex items-center">
        <font-awesome-icon
          icon="chevron-left"
          class="h-8 text-2xl mr-1 md:mr-4"
        />
        <span class="flex flex-col">
          <span class="text-base sm:text-lg lg:text-xl text-left"
            >{{ t('chapter') }} 98</span
          >
          <span class="text-sm md:text-base font-bold text-left">
            story.previousChapter
          </span>
        </span>
      </div>
      <div class="flex items-center">
        <span class="flex flex-col">
          <span class="text-base sm:text-lg lg:text-xl text-right"
            >{{ t('chapter') }} 99</span
          >
          <span class="text-sm md:text-base font-bold text-right">
            story.nextChapter
          </span>
        </span>
        <font-awesome-icon
          icon="chevron-right"
          class="h-8 text-2xl ml-1 md:ml-2"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { computed, onMounted, reactive } from 'vue'
import { useI18n } from 'vue-i18n'

export default {
  props: {
    story: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const data = reactive({})
    onMounted(() => {
      data.storyCategories = computed(() => {
        let themes = []
        if (props.story.story_themes) {
          themes = props.story.story_themes.map((theme) => theme.title)
        }
        return themes.join(', ')
      })
    })

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          categories: 'Catégories',
          chapter: 'Chapitre',
        },
      },
    })

    return { data, t }
  },
}
</script>
