<template>
  <div class="mt-5 p-4 text-accent bg-opacity-100 border-2 rounded-lg">
    <p>{{ t('an-error-occured') }}</p>
    <ul>
      <li v-for="formViolation in formViolations" :key="formViolation">
        {{
          formViolation.field.charAt(0).toUpperCase() +
          formViolation.field.slice(1)
        }}
        :
        {{ formViolation.message }}
      </li>
    </ul>
  </div>
</template>

<script>
import { useI18n } from 'vue-i18n'

export default {
  props: {
    formViolations: {
      required: true,
      type: Array,
      default: () => [],
    },
  },
  setup() {
    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'an-error-occured': "Une erreur s'est produite.",
        },
      },
    })
    return { t }
  },
}
</script>
