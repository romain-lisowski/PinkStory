<template>
  <div
    id="app"
    class="text-center flex flex-col min-h-screen bg-primary text-primary z-10"
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
}
</script>
