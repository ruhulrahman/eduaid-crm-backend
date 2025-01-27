import Vue from 'vue'
import CxltToastr from 'cxlt-vue2-toastr'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'

// Toastr config
// const toastrConfigs = {
//   position: 'top right',
//   errorColor: '#D6E09B',
//   warningColor: '#D6E09B',
//   showDuration: 1000
// }

const toastrConfigs = {
  position: 'top right',
  successColor: '#048008',
  errorColor: '#E44C42',
  warningColor: '#FEB649',
  showDuration: 1000,
  timeOut: 3000
}

Vue.use(CxltToastr, toastrConfigs)
