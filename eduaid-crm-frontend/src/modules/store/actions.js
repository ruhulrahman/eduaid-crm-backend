export default {
  setAccessToken ({ commit }, payload) {
    commit('setAccessToken', payload)
  },
  setAuthUser ({ commit }, payload) {
    commit('setAuthUser', payload)
  },
  updateAuthUser ({ commit }, payload) {
    commit('updateAuthUser', payload)
  },
  setUserPermissions ({ commit }, payload) {
    commit('setUserPermissions', payload)
  },
  updateUserPermissions ({ commit }, payload) {
    commit('updateUserPermissions', payload)
  }
}
