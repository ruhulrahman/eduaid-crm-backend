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
      <!-- <b-breadcrumb-item to="/configurations">Configurations</b-breadcrumb-item> -->
      <b-breadcrumb-item>Configurations</b-breadcrumb-item>
      <b-breadcrumb-item active>Service</b-breadcrumb-item>
    </b-breadcrumb>
      <div class="form-wrapper">
      <b-card title="Service Search">
          <b-card-text>
              <b-row style="font-size: 12px;">
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
                      label="Parent"
                      label-for="parent_id"
                  >
                  <treeselect
                    id="parent_id"
                    :options="parentList"
                    placeholder="Select Parent"
                    v-model="search.parent_id"
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
          <h4 class="card-title mb-0 pl-0">Service List</h4>
        </b-col>
        <b-col class="text-right">
          <b-button v-if="has_permission('add_new_subscription_plan')" size="sm" variant="primary" @click="openAddNewModal()">Add New<i class="ri-add-fill"></i></b-button>
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
                    <th scope="col" class="text-center">Country</th>
                    <th scope="col" class="text-center">Name</th>
                    <th scope="col" class="text-center">Description</th>
                    <th scope="col" class="text-center">Parent</th>
                    <th scope="col" class="text-center">Account</th>
                    <th scope="col" class="text-center">Active</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody v-for="(item, index) in listData" :key="index">
                  <tr style="font-size: 12px;">
                    <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                    <td class="text-center"><span v-if="item.country" v-html="item.country.name"></span></td>
                    <td class="text-left">{{ item.name }}</td>
                    <td class="text-left">{{ item.description }}</td>
                    <td class="text-center"><span v-if="item.parent" v-html="item.parent.name"></span></td>
                    <td class="text-center"><span v-if="item.account" v-html="item.account.name"></span></td>
                    <td class="text-center">
                      <b-form-checkbox v-if="has_permission('active_or_deactive_subscription_plan')" @change="toggleActiveStatus(item)" v-model="item.active" name="check-button" switch>
                      </b-form-checkbox>
                    </td>
                    <td class="text-center">
                      <a v-tooltip="'Edit'" v-if="has_permission('edit_subscription_plan')" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit" @click="editData(item)"><i class="ri-pencil-fill"></i></a>
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
    <b-modal id="modal-1" ref="editModal" size="lg" title="Service" centered :hide-footer="true">
      <Form @reloadList="getListData"
      :userList="userList"
      :parentList="parentList"
      :countryList="countryList"
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
        description: '',
        parent_id: null,
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
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_service_list', params)
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
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/toggle_service_active_status', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({ title: 'Success', message: result.message })
        this.getListData()
        this.getCommonDropdownList()
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
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_service_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getListData()
        this.getCommonDropdownList()
      }
    }
  }
}
</script>
