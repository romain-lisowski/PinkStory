<template>
  <ul
    v-if="formViolations"
    class="mt-5 p-4 text-primary bg-accent bg-opacity-100 rounded-lg"
  >
    <li v-for="formViolation in formViolations" :key="formViolation">
      {{
        formViolation.field.charAt(0).toUpperCase() +
        formViolation.field.slice(1)
      }}
      :
      {{ formViolation.message }}
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    formViolations: {
      type: Array,
      default: null,
    },
  },
}
</script>
