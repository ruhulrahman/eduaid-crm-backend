import moment from 'moment'
import Hashids from 'hashids'
import RestApi, { baseURL } from '@/config'
// import RestApi, { baseURL } from '../config.js'

// var base_url = process.env.VUE_APP_BASE_URL

export default {
  data () {
    return {
      // base_url: base_url,
      initialPagination: {
        //   page: 5,
        rowsPerPage: 20
        //   rowsNumber: 0 //  if getting data from a server
      },
      pagination: {
        currentPage: 1,
        totalRows: 0,
        perPage: 20,
        slOffset: 1
      },
      paginationCustom: {
        currentPage: 1,
        totalRows: 0,
        perPage: 10,
        slOffset: 1
      },
      paginationVerified: {
        currentPage: 1,
        totalRows: 0,
        perPage: 10,
        slOffset: 1
      },
      paginationUnverified: {
        currentPage: 1,
        totalRows: 0,
        perPage: 10,
        slOffset: 1
      },
      monthList: [
        { id: 1, label: 'January' },
        { id: 2, label: 'February' },
        { id: 3, label: 'March' },
        { id: 4, label: 'April' },
        { id: 5, label: 'May' },
        { id: 6, label: 'June' },
        { id: 7, label: 'July' },
        { id: 8, label: 'August' },
        { id: 9, label: 'September' },
        { id: 10, label: 'October' },
        { id: 11, label: 'November' },
        { id: 12, label: 'December' }
      ],
      yearList: [
        { id: 1, label: '2021' },
        { id: 2, label: '2022' },
        { id: 3, label: '2023' },
        { id: 4, label: '2024' },
        { id: 5, label: '2025' }
      ]
    }
  },
  computed: {
    auth_user: function () {
      return this.cn(this.$store.state, 'site.auth_user', null)
    },
    dropdownList: function () {
      if (this.$store.state.dropdowns) {
        return this.$store.state.dropdowns
      } else {
        return {
          countries: []
        }
      }
    }
  },
  methods: {
    //   no_img: function (uri = 'assets/img/placeholder.png') {
    //       return ref.apiUrl(uri)
    //   },
    err_msg: function (err) {
      //  console.log(err.status)

      if (err.status && err.status === 401) {
        this.$router.push('/logout')
        return null
      }

      var msg = 'Request failed to process, try again later.'

      if (err.responseJSON && err.responseJSON.msg) {
        msg = err.responseJSON.msg
      } else if (err.message) {
        msg = err.message
      }

      return msg
    },
    catch_alert: async function (err, callback = null) {
      this.alert(err.message, 'error', callback)
    },
    cn: function (obj, input, IF_FALSE = '') {
      var elements = input.split('.')
      let i = 0

      for (i = 0; i < elements.length; i++) {
        if (!obj || !obj[elements[i]]) {
          return IF_FALSE
        }

        obj = obj[elements[i]]
      }

      if (obj) return obj
      return IF_FALSE
    },
    // limit_str: function (str, limit = 20, alt_str = '...') {
    //   return str.length > limit
    //     ? str.substring(0, limit - 3) + alt_str
    //     : str
    // },
    clone_object: function (value) {
      return Object.assign({}, value)
    },
    str_title: function (str) {
      return str.replace(/\w\S*/g, function (txt) {
        return (
          txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
        )
      })
    },
    checkCurrentDateLessThenDate: function (dateValue) {
      const currentDate = this.dDate(new Date(), 'YYYY-MM-DD')
      const anotherDate = this.dDate(dateValue, 'YYYY-MM-DD')
      if (anotherDate >= currentDate) {
        return true
      } else {
        return false
      }
    },
    dDateReal: function (value, format = 'DD-MM-YYYY') {
      return moment(value).format(format)
    },
    dDate: function (value, format = 'D MMM YYYY') {
      return moment.utc(value).local().format(format)
    },
    dDateTime: function (value, format = 'D MMM YYYY LT') {
      return moment.utc(value).local().format(format)
    },
    dTime: function (value, format = 'LT') {
      return moment.utc(value).local().format(format)
    },
    dRealTime: function (value, format = 'LT') {
      return moment(value).local().format(format)
    },
    rTime: function (value) {
      var today = new Date()
      var year = today.getFullYear()
      var mes = today.getMonth() + 1
      var dia = today.getDate()
      var fecha = year + '-' + mes + '-' + dia
      return moment(fecha + ' ' + value).format('LT')
    },
    dDayDate: function (value, format = 'D MMM YYYY dddd') {
      return moment.utc(value).local().format(format)
    },
    getBetweenHour: function (startTime, endTime) {
      const myEndTime = endTime ? new Date(endTime) : new Date()
      const myStartTime = new Date(startTime)
      const diffMs = myEndTime - myStartTime
      const diffHrs = Math.floor((diffMs % 86400000) / 3600000)
      const diffMins = Math.round(
        ((diffMs % 86400000) % 3600000) / 60000
      )
      if (diffHrs > 1) {
        return `${diffHrs} Hours ${diffMins} min`
      } else {
        return `${diffHrs} Hour ${diffMins} min`
      }
    },
    dMonth: function (value, format = 'MMM YYYY') {
      return moment(value).format(format)
    },
    dYear: function (value, format = 'YYYY') {
      return moment(value).format(format)
    },
    dNow: function (value) {
      //   return moment(value).fromNow()
      return moment.utc(value).local().fromNow()
    },
    integer: function (value = 0) {
      const integerValue = value | 0
      return Number(integerValue).toLocaleString()
    },
    float: function (value = 0.0) {
      const integerValue = parseFloat(value)
      return Number(integerValue).toLocaleString()
    },
    float2: function (value = 0.0) {
      const double = parseFloat(value).toFixed(2)
      return Number(double).toLocaleString()
    },
    hash_id: function (value, encode = true) {
      var hashids = new Hashids('hashids_for_passing_url_id', 32)
      if (encode) {
        return hashids.encode(value)
      } else {
        return hashids.decode(value)
      }
    },
    avatarText: function (value) {
      var matches = value.match(/\b(\w)/g)

      if (matches.length === 1) {
        return value.substring(0, 2).toLocaleUpperCase()
      } else {
        const data = matches.filter((item, index) => {
          if (index < 2) {
            return Object.assign({}, item)
          }
        })
        return data.join('')
      }
    },
    random_number: function (limit = 10) {
      return Math.floor(Math.random() * (limit + 1))
    },
    // remove_row: function (array_data, index) {
    //   if (index > -1) array_data.splice(index, 1)
    // },
    img_on_error: function (ev) {
      ev.target.src = 'https://  via.placeholder.com/150?text=No+Image'
    },
    imageRenderer: function (inputEl, callback) {
      const inputImageRenderer = () => {
        const file = inputEl.value.files[0]
        const reader = new FileReader()

        reader.addEventListener(
          'load',
          () => {
            callback(reader.result)
          },
          false
        )

        if (file) {
          reader.readAsDataURL(file)
        }
      }
      return {
        inputImageRenderer
      }
    },
    has_permission: function (permissionCode) {
      const permissionDisable = this.$store.state.permission_disable

      if (permissionDisable) {
        return true
      } else if (permissionCode) {
        if (permissionCode === 'permitted') {
          return true
        } else {
          const permissions = this.cn(this.$store.state.Auth, 'authUser.permission_codes')

          if (permissions && permissions.length) {
            const userPermission = permissions.find(
              (permission) => permission === permissionCode
            )

            return userPermission !== undefined
          } else {
            return false
          }
        }
      } else return false
    },
    chunk: function (arr, len) {
      var chunks = []
      var i = 0
      var n = arr.length

      while (i < n) {
        chunks.push(arr.slice(i, (i += len)))
      }

      return chunks
    },
    getInitials (string) {
      var names = string.split(' ')
      var initials = names[0].substring(0, 1).toUpperCase()

      if (names.length > 1) {
        initials += names[names.length - 1]
          .substring(0, 1)
          .toUpperCase()
      }
      return initials
    },
    getStatuseByGroupCode: function (statusGroupList, groupCode) {
      const statusGroups = statusGroupList.length ? statusGroupList : this.dropdownList.statusGroups
      if (statusGroups) {
        const screeningGroups = statusGroups.find(
          (item) => item.code === groupCode
        )
        if (screeningGroups) {
          if (
            screeningGroups.statuses &&
            screeningGroups.statuses.length
          ) {
            return screeningGroups.statuses
          } else {
            return []
          }
        } else {
          return []
        }
      } else {
        return []
      }
    },
    getStatuseByCode: function (codeValue) {
      const statusGroups = this.dropdownList.statusGroups
      let statusObj = ''
      if (statusGroups && statusGroups.length) {
        statusGroups.forEach((group) => {
          if (group.statuses && group.statuses.length) {
            group.statuses.forEach((status) => {
              if (status.code === codeValue) {
                statusObj = status
              }
            })
          }
        })
      }
      return statusObj
    },
    makeSelectList: function (list) {
      if (list && list.length) {
        return list.map((row) => {
          return {
            value: row.id,
            text: row.name
          }
        })
      } else return []
    },
    map_bv_select: function (list, id = 'id', label = 'name') {
      if (list && list.length) {
        return list.map((row) => {
          return {
            value: row[id],
            text: row[label]
          }
        })
      } else return []
    },
    go_to: function (uri) {
      return this.$router.push(uri)
    },
    fDate: function (dt) {
      return moment.utc(dt).local().format('DD MMM YYYY')
    },
    fTime: function (dt) {
      return moment.utc(dt).local().format('HH:mm:ss')
    },
    getBase64ImageFromURL (url) {
      return new Promise((resolve, reject) => {
        var img = new Image()
        img.setAttribute('crossOrigin', 'anonymous')

        img.onload = () => {
          var canvas = document.createElement('canvas')
          canvas.width = img.width
          canvas.height = img.height

          var ctx = canvas.getContext('2d')
          ctx.drawImage(img, 0, 0)

          var dataURL = canvas.toDataURL('image/png')

          resolve(dataURL)
        }

        img.onerror = (error) => {
          reject(error)
        }

        img.src = url
      })
    },
    pagination_str: function (data) {
      //  console.log(data)
      if (data) {
        let from = (data.per_page * (data.current_page - 1)) + 1
        let to = (data.per_page * data.current_page)
        const entries = data.total

        if (to > data.total) {
          to = data.total
        }

        if (data.total === 0) {
          from = 0
        }

        return `Showing ${from} to ${to} of ${entries} entries`
      } else {
        return ''
      }
    },
    page_sl: function (pData, index) {
      return (pData.per_page * (pData.current_page - 1)) + (index + 1)
    },
    async getCommonDropdownList () {
      const result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_common_dropdowns_list')
      if (result.success) {
        this.$store.dispatch('mutateCommonDropdwons', result.dropdowns)
      }
    }
  } //    End of method
}
