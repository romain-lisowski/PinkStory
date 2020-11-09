<template>
  <div class="mt-10 text-right">
    {{ $t('filter-by') + ' : ' }}
    <button class="ml-4 px-4 py-3 text-primary bg-accent rounded-lg">
      {{ $t('rate') }}
    </button>
    <button class="ml-4 px-4 py-3 border rounded-lg">
      {{ $t('date') }}
    </button>
    <button class="ml-4 px-4 py-3 border rounded-lg">
      {{ $t('random') }}
    </button>
  </div>
</template>

<script>
export default {
  name: 'StoryListFilter',
}
</script>

<i18n>
{
  "fr": {
    "filter-by": "Filtrer par",
    "rate": "Note",
    "date": "Date",
    "random": "Aléatoire"
  }
}
</i18n>
