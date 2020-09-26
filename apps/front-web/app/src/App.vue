<template>
  <div
    id="app"
    class="text-center flex flex-col min-h-screen"
  >
    <Header />
    <router-view class="font-ps flex-grow" />
    <Footer />
  </div>
</template>

<script>
import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'

export default {
  name: 'App',
  components: {
    Header,
    Footer,
  },
}
</script>
