<template>
  <div
    id="app"
    class="text-center flex flex-col min-h-screen"
  >
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
}
</script>
