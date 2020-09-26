<template>
  <div
    id="app"
    class="text-center "
  >
    <Header />
    <router-view/>
  </div>
</template>

<script>
import Header from '@/components/Header.vue'

export default {
  name: 'App',
  components: {
    Header,
  },
}
</script>
