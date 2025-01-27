<template>
  <div class="section-wrapper">
    <div class="breadcrumb-wrapper">
      <b-breadcrumb class="custom-bread"></b-breadcrumb>
    </div>
      <div class="form-wrapper">
      <b-card title="Search">
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
                      id="mobile"
                      label="Mobile"
                      label-for="mobile"
                  >
                      <b-form-input
                      id="mobile"
                      v-model="search.mobile"
                      type="mobile"
                      placeholder="Enter mobile"
                      required
                      ></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col sm="12" md="3">
                  <b-form-group
                      id="desingation"
                      label="Desingation"
                      label-for="desingation"
                  >
                      <b-form-input
                      id="desingation"
                      v-model="search.desingation"
                      type="search"
                      placeholder="Enter Desination"
                      required
                      ></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col sm="12" md="3">
                  <b-form-group
                      id="role"
                      label="Role"
                      label-for="role"
                  >
                      <b-form-input
                      id="role"
                      v-model="search.role"
                      type="search"
                      placeholder="Enter Role"
                      required
                      ></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col xs="12" sm="12" md="3" lg="3" xl="3">
                  <b-form-group
                    id="date"
                    label="Date"
                    label-for="date"
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
  <b-card class="mt-3" title="Users List">
    <b-row>
      <b-col>
        <div class="table-wrapper table-responsive">
          <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr  style="font-size: 12px;">
                <th scope="col" class="text-center">SL</th>
                <th scope="col" class="text-center">User Information</th>
                <th scope="col" class="text-center">Role</th>
                <th scope="col" class="text-center">Desingation</th>
                <th scope="col" class="text-center">Created Date</th>
                <th scope="col" class="text-center">Status</th>
                <th scope="col" class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr style="font-size: 12px;">
                <td class="text-center">1</td>
                <td class="text-left">Mr.Karim Mia <br> karim@gmail.com <br> 01754512541</td>
                <td class="text-center">Admin</td>
                <td class="text-center">Teacher</td>
                <td class="text-center">10-08-2023</td>
                <td class="text-center"><span class="badge badge-pill badge-info">Active</span></td>
                <td class="text-center">
                  <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                  <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                  <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                </td>
              </tr>
              <tr style="font-size: 12px;">
                <td class="text-center">2</td>
                <td class="text-left">Mr.Rahim Mia <br> rahim@gmail.com <br> 01754512541</td>
                <td class="text-center">Super Admin</td>
                <td class="text-center">Head Teacher</td>
                <td class="text-center">09-08-2023</td>
                <td class="text-center"><span class="badge badge-danger badge-info">InActive</span></td>
                <td class="text-center">
                  <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                  <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                  <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                </td>
              </tr>
              <tr style="font-size: 12px;">
                <td class="text-center">3</td>
                <td class="text-left">Mr.Karim Mia <br> karim@gmail.com <br> 01754512541</td>
                <td class="text-center">Admin</td>
                <td class="text-center">Officer</td>
                <td class="text-center">12-08-2023</td>
                <td class="text-center"><span class="badge badge-pill badge-info">Active</span></td>
                <td class="text-center">
                  <a href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a>
                  <a href="javascript:" class="action-btn status"><i class="ri-eye-fill"></i></a>
                  <a href="javascript:" class="action-btn active"><i class="ri-check-fill"></i></a>
                </td>
              </tr>
              <tr style="font-size: 12px;">
                <td class="text-center">4</td>
                <td class="text-left">Mr.Karim Mia <br> karim@gmail.com <br> 01754512541</td>
                <td class="text-center">Super Admin</td>
                <td class="text-center">Officer</td>
                <td class="text-center">10-05-2023</td>
                <td class="text-center"><span class="badge badge-danger badge-info">InActive</span></td>
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
        name: ''
      },
      ranges: [{ text: '5', value: null }, '5', '10', '15', '20'],
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
