<template>
  <div class="main-wrapper" :class="{ closeSidebar: closeSidebar}">
    <div class="sidebar-section sidebarHide desktop-hidden d-none d-md-block">
      <Sidebar :layout="'designer'"></Sidebar>
    </div>
    <div class="main-content-section containerFullWidth">
      <Header :layout="'designer'"></Header>
      <div class="main-content-wrapper">
        <router-view></router-view>
      </div>
      <Footer></Footer>
    </div>
  </div>
</template>
<script>
import RestApi, { baseURL } from '@/config'
import Sidebar from '../components/Sidebar.vue'
import Header from '../components/Header.vue'
// import Footer from '../components/Footer.vue'
import { EventBus } from '@/EventBusLayout'
import GlobalMixin from '@/mixins/global'

export default {
  name: 'MainLayout',
  mixins: [GlobalMixin],
  data () {
    return {
      closeSidebar: false
    }
  },
  created () {
    EventBus.$on('toggleNav', this.checkSidebarToggleValue)
    this.getAuthUserData()
    this.getCommonDropdownList()
    // this.makeEncryptAllBookContent()
  },
  components: {
    Sidebar,
    Header
    // Footer
  },
  methods: {
    checkSidebarToggleValue (param) {
      this.closeSidebar = param
    },
    async getAuthUserData () {
      this.loading = true
      const result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_auth_user')
      this.loading = false
      if (result.success) {
        this.$store.dispatch('Auth/updateAuthUser', result.auth_user)
        this.$store.dispatch('Auth/updateUserPermissions', result.user_permissions)
      }
    }
    // async makeEncryptAllBookContent () {
    //   this.loading = true
    //   await RestApi.getData(baseURL, 'api/v1/admin/ajax/make_encrypt_all_book_contents')
    // }
  }
}
</script>
<style>
.header-wrapper .navbar {
  background: #2d3436 !important;
}
.main-wrapper .sidebar-section {
  background: #2d3436 !important;
}
.main-wrapper .sidebar-section .sidebar-wrapper .logo-wrapper {
  background: #1c1c1c !important;
}
.section-wrapper .card-body .card-title {
    background-color: #c5d1cd !important;
}
.table thead tr {
  background: #abbec6;
}

.table th, .table td {
    padding: 7px !important;
}

.form-control {
    background-color: #f8f8f8 !important;
}

.vue-treeselect__control {
    height: 31px !important;
}

.main-wrapper .sidebar-section .sidebar-wrapper .sidebar-navigation ul li a {
    padding: 10px 0 10px 12px!important;
}
.main-wrapper .sidebar-section .sidebar-wrapper .sidebar-navigation > ul {
    margin-bottom: 0px!important;
}

/* Font Size */
.font-size-10 { font-size: 10px !important }
.font-size-11 { font-size: 11px !important }
.font-size-12 { font-size: 12px !important }
.font-size-13 { font-size: 13px !important }
.font-size-14 { font-size: 14px !important }
.font-size-15 { font-size: 15px !important }
.font-size-16 { font-size: 16px !important }
.font-size-18 { font-size: 18px !important }
.font-size-20 { font-size: 20px !important }
.font-size-22 { font-size: 22px !important }
.font-size-24 { font-size: 24px !important }
.font-size-26 { font-size: 26px !important }
.font-size-28 { font-size: 28px !important }
.font-size-30 { font-size: 30px !important }
.font-size-32 { font-size: 32px !important }
.font-size-34 { font-size: 34px !important }
.font-size-36 { font-size: 36px !important }

/* text-bold */
.text-bold-300 { font-weight: 300 !important }
.text-bold-400 { font-weight: 400 !important }
.text-bold-500 { font-weight: 500 !important }
.text-bold-600 { font-weight: 600 !important }
.text-bold-700 { font-weight: 700 !important }
.text-bold-800 { font-weight: 800 !important }
.text-bold-900 { font-weight: 900 !important }

.text-underlined { text-decoration: underline !important }
.text-underlined-hover:hover { text-decoration: underline !important }
.text-underlined-none { text-decoration: none !important }

html, body, div, span, applet, object, iframe, blockquote, pre, h1, h2, h3, h4, h5, h6, p, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video {
    line-height: 1.4;
}
/* body {
    font-size: 12px;
} */
.hand { cursor: pointer !important; }
</style>
