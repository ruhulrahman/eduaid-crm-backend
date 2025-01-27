// import toggleStatus from '@/utils/toggle-status'
// import toggleMixedStatus from '@/utils/toggle-mixed-status'

export default {
  data () {
    return {
      pagination: {
        currentPage: 1,
        totalRows: 0,
        perPage: 1,
        slOffset: 1,
        totalSeen: 0
      },
      editItemId: 0,
      detailItemId: 0
    }
  },
  computed: {
    // loading () {
    //   return this.$store.state.commonObj.loading
    // },
    // listReload () {
    //   return this.$store.state.commonObj.listReload
    // },
    // loadingState () {
    //   if (this.listReload) {
    //     return true
    //   } else if (this.loading) {
    //     return true
    //   } else {
    //     return false
    //   }
    // },
    // listData () {
    //   return this.$store.state.list
    // },
    checkBn () {
      return (this.$i18n.locale === 'bn') ? 1 : 0
    }
  },
  watch: {
    listReload: function (newVal, oldVal) {
      if (newVal !== oldVal) {
        this.loadData()
      }
    }
  },
  // mounted () {
  //   core.index()
  // },
  methods: {
    resetId () {
      this.editItemId = 0
    },
    edit (item) {
      this.editItemId = item.id
    },
    details (item) {
      this.items = item
      this.detailItemId = item.id
    },
    paginationData (data) {
      this.pagination.currentPage = data.current_page
      this.pagination.totalRows = data.total
      this.pagination.perPage = data.per_page
      this.pagination.slOffset = data.per_page * this.pagination.currentPage - data.per_page + 1
      this.pagination.totalSeen = this.pagination.totalSeen + data.to + this.pagination.currentPage
    }
    // changeStatus (baseUrl, uri, item, destination = null, dropdownName = null) {
    //   toggleStatus(baseUrl, uri, item, destination, dropdownName)
    // },
    // swalMixedStatus (baseUrl, uri, item, swalMsg = null, hideModal = null) {
    //   toggleMixedStatus(baseUrl, uri, item, swalMsg, hideModal)
    // }
  },
  beforeDestroy () {
    this.$store.dispatch('resetList', [])
  }
}
