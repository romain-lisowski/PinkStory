<template>
  <div
    id="app"
    class="text-center flex flex-col min-h-screen bg-primary text-primary z-10"
  >
    <AdultModal />
    <LayoutHeader />
    <router-view class="flex-grow" />
    <LayoutFooter />
  </div>
</template>

<script>
import AdultModal from '@/components/AdultModal.vue'
import LayoutHeader from '@/components/layout/LayoutHeader.vue'
import LayoutFooter from '@/components/layout/LayoutFooter.vue'

export default {
  name: 'App',
  components: {
    AdultModal,
    LayoutHeader,
    LayoutFooter,
  },
}
</script>
