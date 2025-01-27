<template>
  <div class="section-wrapper">
    <div class="breadcrumb-wrapper">
      <b-breadcrumb class="custom-bread"></b-breadcrumb>
    </div>
      <div class="form-wrapper">
      <b-card title="Abandoned Cart Search">
        <b-card-text>
          <b-row style="font-size: 12px;">
            <b-col sm="12" md="3">
              <b-form-group
                  id="name"
                  label="Name"
                  label-for="name"
              >
                  <b-form-input
                  id="name"
                  v-model="search.name"
                  type="text"
                  placeholder="Enter Name"
                  required
                  ></b-form-input>
              </b-form-group>
            </b-col>
            <b-col sm="12" md="3">
              <b-form-group
                  id="email"
                  label="Email"
                  label-for="email"
              >
                  <b-form-input
                  id="name"
                  v-model="search.email"
                  type="email"
                  placeholder="Enter email"
                  required
                  ></b-form-input>
              </b-form-group>
            </b-col>
            <b-col sm="12" md="3">
              <b-form-group
                  id="phone_no"
                  label="Phone"
                  label-for="phone"
              >
                  <b-form-input
                  id="phone"
                  v-model="search.phone"
                  type="number"
                  placeholder="Enter phone"
                  required
                  ></b-form-input>
              </b-form-group>
            </b-col>
            <b-col sm="12" md="3">
              <b-form-group
                  id="device"
                  label="Device"
                  label-for="device"
              >
                  <b-form-input
                  id="mobile"
                  v-model="search.device"
                  type="text"
                  placeholder="Enter device"
                  required
                  ></b-form-input>
              </b-form-group>
            </b-col>
            <b-col sm="12" md="3">
              <b-form-group
                id="gender"
                label="Gender"
                label-for="gender"
              >
                <b-form-select
                id="Chapter"
                v-model="search.gender"
                :options="genderList"
                required
                >
                <template v-slot:first>
                  <b-form-select-option :value=0>Select</b-form-select-option>
                </template>
              </b-form-select>
              </b-form-group>
            </b-col>
            <b-col xs="12" sm="12" md="3" lg="3" xl="3">
              <b-form-group
                id="date"
                label="Date"
                label-for="gender"
              >
                <flat-pickr
                  id="date"
                  v-model="search.date"
                  class="form-control"
                  placeholder="Date"
                />
              </b-form-group>
            </b-col>
            <b-col sm="12" md="3">
              <br>
              <b-button size="sm" variant="primary"><i class="ri-search-line"></i> Search</b-button>
            </b-col>
          </b-row>
        </b-card-text>
      </b-card>
  </div>
  <b-card class="mt-3" title="Abandoned Cart List">
    <b-row>
      <b-col>
        <div class="table-wrapper table-responsive">
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr style="font-size: 12px;">
                <th scope="col" class="text-center">SL</th>
                <th scope="col" class="text-center">Date</th>
                <th scope="col" class="text-center">Name</th>
                <th scope="col" class="text-center">Phone Number</th>
                <th scope="col" class="text-center">Email</th>
                <th scope="col" class="text-center">Device</th>
                <th scope="col" class="text-center">Gender</th>
                <th scope="col" class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr style="font-size: 12px;">
                <td class="text-center">1</td>
                <td class="text-center">15-10-2023</td>
                <td class="text-left">Karim</td>
                <td class="text-center">01742158456</td>
                <td class="text-center">Karim@gmail.com</td>
                <td class="text-center">IOS</td>
                <td class="text-center">Male</td>
                <td class="text-center">
                  <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                  <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                  <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                </td>
              </tr>
              <tr style="font-size: 12px;">
                <td class="text-center">2</td>
                <td class="text-center">15-10-2023</td>
                <td class="text-left">Sofia</td>
                <td class="text-center">01742158456</td>
                <td class="text-center">Sofia@gmail.com</td>
                <td class="text-center">IOS</td>
                <td class="text-center">Female</td>
                <td class="text-center">
                  <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                  <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                  <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                </td>
              </tr>
              <tr style="font-size: 12px;">
                <td class="text-center">3</td>
                <td class="text-center">15-10-2023</td>
                <td class="text-left">Karim</td>
                <td class="text-center">01742158456</td>
                <td class="text-center">Karim@gmail.com</td>
                <td class="text-center">Android</td>
                <td class="text-center">Male</td>
                <td class="text-center">
                  <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                  <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                  <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                </td>
              </tr>
              <tr style="font-size: 12px;">
                <td class="text-center">4</td>
                <td class="text-center">15-10-2023</td>
                <td class="text-left">Elizabeth</td>
                <td class="text-center">01742158456</td>
                <td class="text-center">elizabeth@gmail.com</td>
                <td class="text-center">Android</td>
                <td class="text-center">Female</td>
                <td class="text-center">
                  <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                  <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                  <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                </td>
              </tr>
              <tr style="font-size: 12px;">
                <td class="text-center">5</td>
                <td class="text-center">15-10-2023</td>
                <td class="text-left">Elizabeth</td>
                <td class="text-center">01742158456</td>
                <td class="text-center">elizabeth@gmail.com</td>
                <td class="text-center">Android</td>
                <td class="text-center">Female</td>
                <td class="text-center">
                  <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                  <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                  <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                </td>
              </tr>
              <tr style="font-size: 12px;">
                <td class="text-center">6</td>
                <td class="text-center">15-10-2023</td>
                <td class="text-left">Elizabeth</td>
                <td class="text-center">01742158456</td>
                <td class="text-center">elizabeth@gmail.com</td>
                <td class="text-center">Android</td>
                <td class="text-center">Female</td>
                <td class="text-center">
                  <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                  <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                  <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </b-col>
    </b-row>
  </b-card>
    <div class="pagination-wrapper mt-4">
      <span>Showing 5 from 30 entries</span>
      <b-pagination v-model="currentPage" :total-rows="rows" size="sm"></b-pagination>
    </div>
  </div>
</template>

<script>
import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
export default {
  components: {
    flatPickr
  },
  data () {
    return {
      // pagination
      rows: 100,
      currentPage: 1,
      // form data
      search: {
        date: '',
        start_date: '',
        end_date: '',
        gender: 0
      },
      ranges: [{ text: '5', value: null }, '5', '10', '15', '20'],
      genderList: [
        { value: 1, text: 'Male' },
        { value: 2, text: 'Female' }
      ],
      value: '',
      // table data
      sortBy: 'user',
      sortDesc: false
    }
  },
  methods: {
    deleteConfirmation () {
      this.$swal({
        title: 'Are you sure to delete ?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          // declare confirmed method to hit api
          this.approveToastr()
        }
      })
    },
    approveToastr () {
      this.$toast.success({
        title: 'Success',
        message: 'Data deleted successfully'
      })
    }
  }
}
</script>
