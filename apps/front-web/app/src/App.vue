<template>
  <div
    id="app"
    class="text-center flex flex-col min-h-screen bg-primary text-primary tracking-tight"
  >
    <LayoutLoadingOverlay />
    <LayoutAdultModal />
    <LayoutHeader />
    <router-view class="flex-grow" />
    <LayoutFooter />
  </div>
</template>

<script>
import LayoutLoadingOverlay from '@/components/layout/LayoutLoadingOverlay.vue'
import LayoutAdultModal from '@/components/layout/LayoutAdultModal.vue'
import LayoutHeader from '@/components/layout/LayoutHeader.vue'
import LayoutFooter from '@/components/layout/LayoutFooter.vue'

export default {
  components: {
    LayoutLoadingOverlay,
    LayoutAdultModal,
    LayoutHeader,
    LayoutFooter,
  },
}
</script>
