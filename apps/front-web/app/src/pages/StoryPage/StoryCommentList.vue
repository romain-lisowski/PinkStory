<template>
  <div class="w-5/6 lg:w-2/3">
    <p class="text-3xl md:text-5xl font-bold mt-10">{{ t('comments') }}</p>

    <div v-for="comment in comments" :key="comment.id">
      <span class="flex items-center">
        <img
          class="mt-8 mx-auto p-1/2 md:p-1 w-16 md:w-24 border-2 border-accent rounded-full"
          :src="require('@/assets/images/profil.jpg')"
        />
        <span class="flex-1 flex flex-col mt-8 ml-4 text-left">
          <span class="text-lg md:text-xl font-bold">{{ comment.author }}</span>
          <span class="mt-1 text-sm md:text-base">{{ comment.postDate }}</span>
        </span>
      </span>
      <p
        class="mt-4 lg:mt-8 p-4 text-base md:text-lg font-light text-left bg-primary-inverse bg-opacity-5 tracking-wide rounded-xl"
      >
        {{ comment.content }}
      </p>
    </div>
    <button
      class="my-12 w-full py-4 text-lg bg-accent tracking-wide rounded-lg"
    >
      {{ t('comment-this-chapter') }}
    </button>
  </div>
</template>

<script>
export default {
  name: 'StoryCommentList',
  props: {
    comments: {
      type: Array,
      required: true,
    },
  },
}
</script>

<!-- <i18n>
{
  "fr": {
    "comments": "Commentaires",
    "comment-this-chapter": "Commenter ce chapitre"
  }
}
</i18n> -->
