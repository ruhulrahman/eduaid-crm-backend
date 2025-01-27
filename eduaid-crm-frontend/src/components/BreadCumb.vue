<template>
  <div class="breadcrumb-wrapper">
      <b-breadcrumb class="custom-bread" :items="breadcrumbs"></b-breadcrumb>
      <!-- <p class="time-logger">{{ $t('globalTrans.welcomeTo') }}, tEST</p> -->
  </div>
</template>
<script>
export default {
  data () {
    return {
      breadcrumbs: [],
      currentUser: ''
    }
  },
  created: function () {
    var currentUrl = window.location.pathname
    console.log('currentUrl', this.currentUrl)
    const tmpBreadcumbs = currentUrl.split('/')
    tmpBreadcumbs.shift()
    const bread = []
    tmpBreadcumbs.map(item => {
      if (!Number.isInteger(parseInt(item))) {
        bread.push({ text: item.toUpperCase().split('-').join(' ') })
        return Object.assign({}, { text: item.toUpperCase().split('-').join(' ') })
      }
    })
    this.breadcrumbs = bread
  },
  computed: {
  }
}
</script>
