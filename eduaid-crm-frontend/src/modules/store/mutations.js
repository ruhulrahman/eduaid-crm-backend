export const mutations = {
  setAccessToken (state, payload) {
    state.accessToken = payload
  },
  setAuthUser (state, payload) {
    state.authUser = Object.assign({}, payload)
  },
  updateAuthUser (state, payload) {
    state.authUser = Object.assign({}, state.authUser, payload)
  },
  setUserPermissions (state, payload) {
    state.userPermissions = Object.assign({}, payload)
  },
  updateUserPermissions (state, payload) {
    state.userPermissions = Object.assign({}, state.userPermissions, payload)
  }
}
