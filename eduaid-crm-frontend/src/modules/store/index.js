import actions from './actions'
import getters from './getters'
import { mutations } from './mutations'

const state = {
  isLoggedIn: false,
  accessToken: null,
  authUser: '',
  userPermissions: ''
}

export default {
  namespaced: true,
  state,
  actions,
  getters,
  mutations
}
