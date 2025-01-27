<template>
    <div class="header-wrapper">
        <navbar :layout="layout" />
    </div>
</template>
<script>
import Navbar from './Navbar.vue'
export default {
  name: 'Header',
  props: ['layout'],
  components: {
    Navbar
  }
}
</script>
