import Vue from 'vue'
import App from './App.vue'
// import './registerServiceWorker'
import router from './router'
import store from './store'
import './plugins'
import i18n from './i18n'
import listMixin from './mixins/list.js'
import globalMixin from './mixins/global.js'
import VTooltip from 'v-tooltip'
import VueQuillEditor from 'vue-quill-editor'

import 'quill/dist/quill.core.css' // import styles
import 'quill/dist/quill.snow.css' // for snow theme
import 'quill/dist/quill.bubble.css' // for bubble theme

Vue.use(VTooltip)

Vue.mixin(listMixin)
Vue.mixin(globalMixin)
Vue.use(VueQuillEditor)
Vue.config.productionTip = false

router.beforeEach((to, from, next) => {
  var apiToken = localStorage.getItem('access_token')

  if (to.matched.some(record => record.meta.auth) && !apiToken) {
    sessionStorage.setItem('redirectPath', to.path)
    next('/')
    // next({ name: 'account-signin', query: { next: to.fullPath } })
  } else {
    next()
  }
})

const vm = new Vue({
  router,
  store,
  i18n,
  render: h => h(App)
}).$mount('#app')

window.vm = vm
