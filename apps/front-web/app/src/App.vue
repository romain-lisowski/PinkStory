<template>
  <div id="app" class="text-center flex flex-col min-h-screen bg-primary">
    <LayoutHeader />
    <router-view class="font-ps flex-grow" />
    <LayoutFooter />
  </div>
</template>

<script>
import LayoutHeader from '@/components/layout/LayoutHeader.vue'
import LayoutFooter from '@/components/layout/LayoutFooter.vue'

export default {
  name: 'App',
  components: {
    LayoutHeader,
    LayoutFooter,
  },
  created() {
    this.setTheme()
  },
  methods: {
    setTheme() {
      if (
        window.matchMedia &&
        window.matchMedia('(prefers-color-scheme: dark)').matches
      ) {
        console.log('dark')
        document.documentElement.classList.add('theme-dark')
      } else {
        console.log('light')
        document.documentElement.classList.remove('theme-dark')
      }
    },
  },
}
</script>
