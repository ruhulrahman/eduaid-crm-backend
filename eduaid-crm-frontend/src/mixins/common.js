export default {
  computed: {
    checkBn () {
      return (this.$i18n.locale === 'bn') ? 1 : 0
    }
  },
  methods: {
    getCommonStoreData (state, Id) {
      if (state) {
        const tmpData = this.$store.state.commonObj[state]
        if (typeof tmpData === 'undefined') {
          return ''
        } else {
          const data = tmpData.find(item => item.value === Id)
          if (typeof data === 'undefined') {
            return ''
          } else {
            return data.text
          }
        }
      }
    },
    getCommonConfigStoreData (state, Id) {
      if (state) {
        const tmpData = this.$store.state.commonObjCommonConfig[state]
        if (typeof tmpData === 'undefined') {
          return ''
        } else {
          const data = tmpData.find(item => item.value === Id)
          if (typeof data === 'undefined') {
            return ''
          } else {
            return data.text
          }
        }
      }
    },
    getStoreData (ServiceName, state, Id) {
      if (state) {
        const tmpData = this.$store.state[ServiceName].commonObj[state]
        if (typeof tmpData === 'undefined') {
          return ''
        } else {
          const data = tmpData.find(item => item.value === Id)
          if (typeof data === 'undefined') {
            return ''
          } else {
            return data.text
          }
        }
      }
    },
    getMainStoreData (state, Id) {
      if (state) {
        const tmpData = this.$store.state[state]
        if (typeof tmpData === 'undefined') {
          return ''
        } else {
          const data = tmpData.find(item => item.value === Id)
          if (typeof data === 'undefined') {
            return ''
          } else {
            return data.text
          }
        }
      }
    }
  }
}
