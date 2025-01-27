import RestApi, { apiBaseUrl } from '@/config/api_config'

export default {
  computed: {
    hasDropdownLoadedCommonService () {
      return this.$store.state.CommonService.commonObj.hasDropdownLoaded
    }
  },
  watch: {
    hasDropdownLoadedCommonService: function (newValue) {
      if (!newValue) {
        this.loadCommonDropdown()
      }
    }
  },
  created () {
    this.loadCommonDropdown()
  },
  methods: {
    loadCommonDropdown () {
      RestApi.getData(apiBaseUrl, 'common-dropdowns', null).then(response => {
        if (response.success) {
          this.$store.dispatch('CommonService/mutateCommonObj', {
            hasDropdownLoaded: true

          })
          this.$store.dispatch('CommonService/localizeDropdown', { value: this.$i18n.locale })
        }
      })
    }
  }
}
