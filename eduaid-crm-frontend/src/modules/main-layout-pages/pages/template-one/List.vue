<template>
    <div class="section-wrapper">
      <div class="breadcrumb-wrapper">
        <b-breadcrumb class="custom-bread" :items="breadcrumbs"></b-breadcrumb>
        <p class="time-logger">Welcome, Mohiuddin | <i class="ri-time-line"></i> Last Login: 1 min ago</p>
      </div>
        <div class="form-wrapper">
        <b-card title="Fine Receipt">
            <b-card-text>
                <b-row>
                  <b-col sm="12" md="4">
                    <b-form-group
                        id="division"
                        label="Division Name"
                        label-for="divisionInput"
                    >
                        <b-form-select
                        id="divisionInput"
                        v-model="form.division"
                        :options="divisions"
                        required
                        ></b-form-select>
                    </b-form-group>
                  </b-col>
                  <b-col sm="12" md="4">
                    <b-form-group
                        id="district"
                        label="District Name"
                        label-for="districtInput">
                        <b-form-select
                        id="districtInput"
                        v-model="form.district"
                        :options="districts"
                        required
                        ></b-form-select>
                    </b-form-group>
                  </b-col>
                  <b-col sm="12" md="4">
                    <b-form-group
                        id="name"
                        label="Upozila Name"
                        label-for="upozilaInput">
                        <b-form-select
                        id="upozilaInput"
                        v-model="form.upozila"
                        :options="upozilas"
                        required
                        ></b-form-select>
                    </b-form-group>
                  </b-col>
                </b-row>
                <b-row>
                  <b-col sm="12" md="4">
                    <b-form-group
                        id="market"
                        label="Market Name"
                        label-for="marketInput"
                    >
                        <b-form-input
                        id="marketInput"
                        v-model="form.market"
                        type="text"
                        placeholder="Enter Market Name"
                        required
                        ></b-form-input>
                    </b-form-group>
                  </b-col>
                  <b-col sm="12" md="4">
                    <b-form-group
                        id="date"
                        label="Select Date"
                        label-for="selectDate">
                        <b-form-datepicker
                        id="selectDate"
                        v-model="value"
                        b-form-datepicker
                        required
                        ></b-form-datepicker>
                    </b-form-group>
                  </b-col>
                  <b-col sm="12" md="4">
                    <br>
                    <b-button size="sm" variant="primary"><i class="ri-search-line"></i> Search</b-button>
                  </b-col>
                </b-row>
            </b-card-text>
        </b-card>
    </div>

    <b-card class="mt-3" title="Fine Receipt List">
      <b-card-text>
          <b-row class="table-filter mb-2">
            <b-col md="2">
              <div class="d-flex justify-between align-items-center">
                <span>Show</span>
              <b-form-group id="table-row" class="mb-0 mx-1">
                <b-form-select
                id="row-range"
                v-model="form.range"
                :options="ranges"
                required
                ></b-form-select>
              </b-form-group>
              <span>entries</span>
              </div>
            </b-col>
            <b-col md="3">
              <b-input-group>
              <b-input-group-prepend is-text>
                <i class="ri-search-line"></i>
              </b-input-group-prepend>
              <b-form-input aria-label="Text"></b-form-input>
            </b-input-group>
            </b-col>
            <b-col>
              <span class="d-flex justify-content-end">
                <b-button size="sm" variant="primary" class="mr-2">Download CSV <i class="ri-download-2-line"></i></b-button>
                <b-button size="sm" variant="info" v-b-modal.modal-1>{{ $t('globalTrans.add_new') }} <i class="ri-add-fill"></i></b-button>
              </span>
            </b-col>
          </b-row>
        <div class="table-wrapper">
        <b-table
          :items="items"
          :fields="fields"
          :sort-by.sync="sortBy"
          :sort-desc.sync="sortDesc"
          sort-icon-left
          :striped="true"
          :hover="true"
          :bordered="true"
          responsive="sm"
        >
          <template v-slot:cell(action)="">
            <a href="javascript:" class="action-btn edit" :title="$t('globalTrans.edit')"><i class="ri-pencil-fill"></i></a>
            <a href="javascript:" class="action-btn status" title="Status"><i class="ri-eye-fill"></i></a>
            <a href="javascript:" class="action-btn active" title="Active"><i class="ri-check-fill"></i></a>
            <!-- <a href="javascript:" class="action-btn delete" title="Delete" @click="deleteConfirmation"><i class="ri-delete-bin-2-line"></i></a> -->
          </template>
        </b-table>
      </div>
      </b-card-text>
    </b-card>
    <!--add new modal -->
    <b-modal
      id="modal-1"
      ok-title="Submit"
      ok-variant="success"
      cancel-variant="danger"
      cancel-title="Cancel"
      centered
      title="Fine Collector Information">
      <Form/>
    </b-modal>
      <!-- pagination -->
      <div class="pagination-wrapper mt-4">
        <span>Showing 5 from 30 entries</span>
        <b-pagination v-model="currentPage" :total-rows="rows" size="sm"></b-pagination>
      </div>
    </div>
</template>

<script>
import Form from './Form.vue'
export default {
  components: {
    Form
  },

  data () {
    return {
      // breadcrumb data
      breadcrumbs: [
        {
          text: 'Home',
          href: '#'
        },
        {
          text: 'Bazar Monitoring System',
          href: '/'
        },
        {
          text: 'Fine Receipt',
          active: true
        }
      ],
      // pagination
      rows: 100,
      currentPage: 1,
      // form data
      form: {
        range: null,
        division: null,
        district: null,
        upozila: null
      },
      ranges: [{ text: '5', value: null }, '5', '10', '15', '20'],
      divisions: [{ text: 'Select Division', value: null }, 'Dhaka', 'Chattagram', 'Barisal', 'Sylhet'],
      districts: [{ text: 'Select District', value: null }, 'Dhaka', 'Narayanganj', 'Savar', 'Gazipur'],
      upozilas: [{ text: 'Select Upozila', value: null }, 'Bandor', 'Rupganj', 'Sonargaon', 'Araihazar'],
      value: '',
      // table data
      sortBy: 'division',
      sortDesc: false,
      fields: [
        { key: 'sl', sortable: true },
        { key: 'division', sortable: true },
        { key: 'district', sortable: true },
        { key: 'upozila', sortable: true },
        { key: 'market_name', sortable: true },
        { key: 'complain_no', sortable: true },
        { key: 'conviction_date', sortable: true },
        { key: 'action', sortable: false }
      ],
      items: [
        {
          isActive: true,
          sl: 1,
          division: 'Dhaka',
          district: 'Dhaka',
          upozila: 'DNCC',
          market_name: 'Abdullahpur Bazar',
          complain_no: 'Com-22/01',
          conviction_date: '27.01.2022',
          action: ''
        },
        {
          isActive: true,
          sl: 2,
          division: 'Chattagram',
          district: 'Chandpur',
          upozila: 'Hazigonj',
          market_name: 'Hazigonj Bazar',
          complain_no: 'Com-22/02',
          conviction_date: '28.01.2022',
          action: ''
        },
        {
          isActive: true,
          sl: 3,
          division: 'Barishal',
          district: 'Barishal',
          upozila: 'Banaripara',
          market_name: 'Notun Bazar',
          complain_no: 'Com-22/03',
          conviction_date: '29.01.2022',
          action: ''
        },
        {
          isActive: true,
          sl: 4,
          division: 'Khulna',
          district: 'Chuadanga',
          upozila: 'Alamdanga',
          market_name: 'Romij Shopping',
          complain_no: 'Com-22/04',
          conviction_date: '30.01.2022',
          action: ''
        },
        {
          isActive: true,
          sl: 5,
          division: 'Khulna',
          district: 'Chuadanga',
          upozila: 'Alamdanga',
          market_name: 'Romij Shopping',
          complain_no: 'Com-22/04',
          conviction_date: '30.01.2022',
          action: ''
        }
      ]
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
        message: 'Data deleted successfully',
        color: '#D6E09B'
      })
    }
  }
}
</script>
