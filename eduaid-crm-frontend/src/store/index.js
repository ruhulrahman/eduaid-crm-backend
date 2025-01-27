import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import actions from './actions'
import mutations from './mutations'
import getters from './getters'
import Custom from './custom'
import Auth from '../modules/store'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    Auth
  },
  state: {
    list: [],
    notificationList: [],
    toggleNotification: false,
    permission_disable: true,
    dropdowns: {
      countries: [],
      statuses: [],
      services: [],
      yearList: Custom.yearList
    },
    commonObj: {
      hasDropdownLoaded: false,
      dateFormat: 'DD/MM/YYYY',
      timeFormat: 'h:m',
      unitOfTime: 'day',
      loading: false,
      listReload: false,
      monthList: Custom.monthList,
      yearList: Custom.yearList,
      weekList: Custom.weekList
    }
  },
  actions,
  mutations,
  getters,
  plugins: [createPersistedState()]
})
