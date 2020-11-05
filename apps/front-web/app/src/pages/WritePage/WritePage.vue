<template>
  <div
    class="flex flex-col justify-center items-center bg-cover"
    :style="{
      'background-image': `url(${require('@/assets/images/book.jpg')})`,
    }"
  >
    <p class="mt-32 font-bold text-4xl sm:text-5xl lg:text-5xl">
      {{ $t('Ecrire une histoire') }}
    </p>
    <div class="w-3/4 sm:w-1/2 lg:w-2/3 xl:w-3/4 my-10">
      <div class="flex flex-col gap-8 items-center justify-center">
        <div
          class="mb-4 px-10 pt-6 pb-10 bg-primary rounded-xl opacity-75 w-full"
        >
          <input
            id="title"
            type="text"
            name="title"
            :placeholder="$t('title')"
            class="my-5 p-3 w-full text-primary-inverse bg-primary-inverse rounded-md"
          />

          <editor-menu-bar
            v-slot="{ commands, isActive }"
            class="text-left"
            :editor="editor"
            :keep-in-bounds="keepInBounds"
          >
            <div class="menubar">
              <button
                class="px-2 py-1"
                :class="{ 'bg-accent': isActive.bold() }"
                @click="commands.bold"
              >
                <font-awesome-icon icon="bold" />
              </button>

              <button
                class="px-2 py-1"
                :class="{
                  'bg-accent': isActive.italic(),
                }"
                @click="commands.italic"
              >
                <font-awesome-icon icon="italic" />
              </button>

              <button
                class="px-2 py-1"
                :class="{ 'bg-accent': isActive.underline() }"
                @click="commands.underline"
              >
                <font-awesome-icon icon="underline" />
              </button>

              <button
                class="px-2 py-1"
                :class="{ 'bg-accent': isActive.blockquote() }"
                @click="commands.blockquote"
              >
                <font-awesome-icon icon="quote-right" />
              </button>

              <button
                class="px-2 py-1"
                :class="{ 'is-active': isActive.heading({ level: 3 }) }"
                @click="commands.heading({ level: 3 })"
              >
                <font-awesome-icon icon="heading" />
              </button>
            </div>
          </editor-menu-bar>

          <editor-content
            id="editor"
            class="text-primary-inverse bg-primary-inverse border text-left"
            :editor="editor"
          />
          <button
            class="mt-6 py-4 text-lg font-light tracking-wide text-primary bg-accent rounded-lg w-full"
            type="submit"
          >
            {{ $t('send') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Editor, EditorContent, EditorMenuBar } from 'tiptap'
import { Bold, Italic, Underline, Blockquote, Heading } from 'tiptap-extensions'

export default {
  name: 'WritePage',
  components: {
    EditorContent,
    EditorMenuBar,
  },
  data() {
    return {
      // Create an `Editor` instance with some default content. The editor is
      // then passed to the `EditorContent` component as a `prop`
      keepInBounds: true,
      editor: new Editor({
        extensions: [
          new Bold(),
          new Italic(),
          new Underline(),
          new Blockquote(),
          new Heading({
            levels: [3],
          }),
        ],
        content: '<h3>New Story</h3><p>This is just a boring paragraph</p>',
      }),
    }
  },
  beforeDestroy() {
    // Always destroy your editor instance when it's no longer needed
    this.editor.destroy()
  },
}
</script>

<i18n>
{
  "fr": {
    "title": "Titre de l'histoire",
    "send": "Envoyer"
  }
}
</i18n>
