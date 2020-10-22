<template>
  <div
    id="app"
    class="text-center flex flex-col min-h-screen bg-primary text-primary tracking-tight"
  >
    <LayoutAdultModal />
    <LayoutHeader />
    <router-view class="flex-grow" />
    <LayoutFooter />
  </div>
</template>

<script>
import LayoutAdultModal from '@/components/layout/LayoutAdultModal.vue'
import LayoutHeader from '@/components/layout/LayoutHeader.vue'
import LayoutFooter from '@/components/layout/LayoutFooter.vue'

export default {
  name: 'App',
  components: {
    LayoutAdultModal,
    LayoutHeader,
    LayoutFooter,
  },
}
</script>
