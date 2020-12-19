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
            class="p-4 bg-primary bg-opacity-100 text-left"
            :editor="editor"
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
        content:
          '<h3>Nouvelle histoire !</h3><p>Commencez à écrire une nouvelle histoire érotique fictive ou réelle ...</p>',
      }),
    }
  },
  beforeUnmount() {
    this.editor.destroy()
  },
}
</script>

<!-- <i18n>
{
  "fr": {
    "write": "Ecrire une histoire",
    "title": "Titre de l'histoire",
    "send": "Envoyer"
  }
}
</i18n> -->
