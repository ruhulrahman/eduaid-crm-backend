<template>
  <div class="section-wrapper">
    <!-- <div class="breadcrumb-wrapper">
      <b-breadcrumb class="custom-bread"></b-breadcrumb>
    </div> -->
    <b-breadcrumb>
      <b-breadcrumb-item to="/dashboard">
        <b-icon icon="house-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
        Dashboard
      </b-breadcrumb-item>
      <b-breadcrumb-item>Lead Management</b-breadcrumb-item>
      <b-breadcrumb-item active>Leads</b-breadcrumb-item>
    </b-breadcrumb>
      <div class="form-wrapper">
      <b-card title="Leads Search">
          <b-card-text>
              <b-row style="font-size: 12px;">
                <b-col sm="12" md="3">
                  <b-form-group
                      label="Name"
                      label-for="name"
                  >
                      <b-form-input
                      id="name"
                      v-model="search.name"
                      type="text"
                      placeholder="Enter Name"
                      ></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col sm="12" md="3">
                  <b-form-group
                      label="Mobile"
                      label-for="name"
                  >
                      <b-form-input
                      id="mobile"
                      v-model="search.mobile"
                      type="text"
                      placeholder="Enter mobile"
                      ></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col sm="12" md="3">
                  <b-form-group
                      label="Country"
                      label-for="country_id"
                  >
                  <treeselect
                    id="country_id"
                    :options="countryList"
                    placeholder="Select Country"
                    v-model="search.country_id"
                    />
                  </b-form-group>
                </b-col>
                <b-col sm="12" md="3">
                  <br>
                  <b-button size="sm" variant="primary" @click="searchData"><i class="ri-search-line"></i> Search</b-button>
                  <b-button size="sm ml-1" variant="danger" @click="clearData"><i class="ri-close-line"></i> Clear</b-button>
                </b-col>
              </b-row>
          </b-card-text>
      </b-card>
  </div>
  <b-card class="mt-3">
    <b-card-title>
      <b-row>
        <b-col>
          <h4 class="card-title mb-0 pl-0">Leads List</h4>
        </b-col>
        <b-col class="text-right">
          <!-- <b-button v-if="has_permission('add_new_subscription_plan')" size="sm" variant="primary" @click="openAddNewModal()">Add New<i class="ri-add-fill"></i></b-button> -->
          <router-link :to="`/add-lead`"><b-button v-if="has_permission('add_new_subscription_plan')" size="sm" variant="primary">Add New<i class="ri-add-fill"></i></b-button></router-link>
        </b-col>
      </b-row>
    </b-card-title>
    <b-row>
      <b-col>
        <b-overlay :show="loading">
          <b-card>
            <div class="table-wrapper table-responsive">
              <table class="table table-striped table-hover table-bordered">
                <thead>
                  <tr style="font-size: 12px;">
                    <th scope="col" class="text-center">SL</th>
                    <th scope="col" class="text-left">Lead Name</th>
                    <th scope="col" class="text-center">DOB</th>
                    <th scope="col" class="text-center">Country</th>
                    <th scope="col" class="text-center">Marital Status</th>
                    <th scope="col" class="text-center">Is Client</th>
                    <th scope="col" class="text-center">Mobile</th>
                    <th scope="col" class="text-center">Active</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody v-for="(item, index) in listData" :key="index">
                  <tr style="font-size: 12px;">
                    <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                    <td class="text-left">{{ item.full_name }}</td>
                    <td class="text-center"><span v-if="item.dob" v-html="dDate(item.dob)"></span></td>
                    <td class="text-center"><span v-if="item.country" v-html="item.country.name"></span></td>
                    <td class="text-center">
                      <span v-if="item.is_married" class="badge badge-success badge-pill">Married</span>
                      <span v-else class="badge badge-danger badge-pill">Unmarried</span>
                    </td>
                    <td class="text-center">
                      <span v-if="item.is_client" class="badge badge-success badge-pill">Yes</span>
                      <span v-else class="badge badge-danger badge-pill">No</span>
                    </td>
                    <td class="text-center">
                      <span v-if="item.mobile_country" v-html="`+${item.mobile_country.calling_code}${item.mobile}`"></span>
                    </td>
                    <td class="text-center">
                      <b-form-checkbox v-if="has_permission('active_or_deactive_subscription_plan')" @change="toggleActiveStatus(item)" v-model="item.active" name="check-button" switch>
                      </b-form-checkbox>
                    </td>
                    <td class="text-center">
                      <router-link :to="`/lead-details/${hash_id(item.id)}`" v-tooltip="'View Details'" style="width: 20px !important; height: 19px !important; font-size:10px" class="action-btn status" @click="detailsData(item)"><i class="ri-eye-fill"></i></router-link>
                      <!-- <a v-tooltip="'Edit'" v-if="has_permission('edit_subscription_plan')" @click="editData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a> -->
                      <router-link :to="`/add-lead/${hash_id(item.id)}`"><a v-tooltip="'Edit'" v-if="has_permission('edit_subscription_plan')" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit"><i class="ri-pencil-fill"></i></a></router-link>
                      <a v-tooltip="'Delete'" v-if="has_permission('delete_subscription_plan')" @click="deleteData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </b-card>
        </b-overlay>
      </b-col>
    </b-row>
  </b-card>
    <b-modal id="modal-1" ref="editModal" size="xl" title="Leads" centered :hide-footer="true">
      <Form @reloadList="getListData"
      :editItem="editItem"/>
    </b-modal>
    <!-- pagination -->
    <div class="pagination-wrapper mt-4">
      <span>Showing {{ pagination.slOffset }} from {{ pagination.totalRows }} entries</span>
      <b-pagination
        size="sm"
        v-model="pagination.currentPage"
        :per-page="pagination.perPage"
        :total-rows="pagination.totalRows"
        @input="searchData"
        />
    </div>
  </div>
</template>

<script>
import Form from './Form.vue'
import RestApi, { baseURL } from '@/config'

// import the component
import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
  components: {
    Form,
    Treeselect
  },
  data () {
    return {
      // pagination
      rows: 100,
      currentPage: 1,
      // form data
      search: {
        name: '',
        mobile: '',
        country_id: null,
        account_id: null
      },
      value: '',
      listData: [],
      parentList: [],
      userList: [],
      loading: false,
      editItem: ''
    }
  },
  computed: {
    countryList: function () {
      return this.cn(this.$store.state.dropdowns, 'countries')
    }
  },
  created () {
    this.getListData()
  },
  mounted () {
    this.getUserList()
  },
  methods: {
    openAddNewModal () {
      this.editItem = ''
      this.$refs.editModal.show()
    },
    editData (item) {
      this.editItem = JSON.stringify(item)
      this.$refs.editModal.show()
    },
    searchData () {
      this.getListData()
    },
    clearData () {
      this.search = {
        name: '',
        description: '',
        parent_id: null,
        country_id: null,
        account_id: null
      }
      this.getListData()
    },
    async getListData () {
      this.loading = true
      const params = Object.assign({}, this.search, { page: this.pagination.currentPage, per_page: this.pagination.perPage })
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_lead_list', params)
      if (result.success) {
        this.parentList = result.parentList
        this.listData = result.data.data
        this.paginationData(result.data)
      }
      this.loading = false
    },
    async getUserList () {
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_user_all_list')
      if (result.success) {
        this.userList = result.data
      }
    },
    async toggleActiveStatus (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/toggle_lead_active_status', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({ title: 'Success', message: result.message })
        this.getListData()
      }
    },
    deleteData (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          // declare confirmed method to hit api
          this.deleteConfirmed(item)
        }
      })
    },
    async deleteConfirmed (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_lead_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getListData()
      }
    }
  }
}
</script>
