export default {
  mutateCommonDropdwons (state, payload) {
    state.dropdowns = Object.assign({}, state.dropdowns, payload)
  },
  isToggleNotification (state, payload) {
    state.toggleNotification = payload
  },
  setList (state, payload) {
    state.list = payload
  },
  resetList (state, payload) {
    state.list = payload
  },
  toggleStatus (state, payload) {
    state.list = state.list.map(function (item) {
      if (item.id === payload.id && item.status === 2) {
        return Object.assign(item, { status: 1 })
      } else if (item.id === payload.id && item.status === 0) {
        return Object.assign(item, { status: 1 })
      }
      return item
    })
  },
  toggleDropdownItemStatus (state, payload) {
    if (payload.destination === 'common_service') {
      state.commonObj[payload.dropdownName] = state.commonObj[payload.dropdownName].map(item => {
        if (item.value === payload.itemId) {
          return Object.assign(item, { status: item.status === 2 ? 1 : 2 })
        }
        return item
      })
    } else {
      // Nothing to do
    }
  }
}
