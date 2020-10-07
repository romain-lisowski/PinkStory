<template>
  <div
    id="app"
    class="text-center flex flex-col min-h-screen bg-primary text-primary"
  >
    <LayoutHeader />
    <router-view class="flex-grow" />
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
        document.documentElement.classList.add('theme-dark')
        document.documentElement.classList.remove('theme-light')
      } else {
        document.documentElement.classList.add('theme-light')
        document.documentElement.classList.remove('theme-dark')
      }
    },
  },
}
</script>
