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
      <b-breadcrumb-item>Configurations</b-breadcrumb-item>
      <b-breadcrumb-item to="/status-groups">Status Groups</b-breadcrumb-item>
      <b-breadcrumb-item active>Statuses</b-breadcrumb-item>
    </b-breadcrumb>
      <div class="form-wrapper">
      <b-card title="Statuses Search">
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
                      label="Code"
                      label-for="code"
                  >
                      <b-form-input
                      id="code"
                      v-model="search.code"
                      type="text"
                      placeholder="Enter Code"
                      ></b-form-input>
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
          <h4 class="card-title mb-0 pl-0">Status List</h4>
        </b-col>
        <b-col class="text-right">
          <b-button v-if="has_permission('permitted')" size="sm" variant="primary" @click="openAddNewModal()">Add New<i class="ri-add-fill"></i></b-button>
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
                    <th scope="col" class="text-center">Name</th>
                    <th scope="col" class="text-center">Code</th>
                    <th scope="col" class="text-center">Color Name</th>
                    <th scope="col" class="text-center">Serial</th>
                    <th scope="col" class="text-center">Active</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody v-for="(item, index) in listData" :key="index">
                  <tr style="font-size: 12px;">
                    <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                    <td class="text-left">
                        <router-link to="/"><span>{{ item.name }}</span></router-link>
                    </td>
                    <td class="text-left">
                      {{ item.code }}
                    </td>
                    <td class="text-center">
                      <span class="badge badge-pill" :class="item.color_name ? 'badge-'+item.color_name : ''" v-html="item.color_name"></span>
                    </td>
                    <td class="text-center">
                      <span class="action-btn btn-status" style="width: 22px !important; height: 22px !important; font-size:12px" v-html="item.serial"></span>
                      <a v-tooltip="'Serial Up'" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit" @click="serialUp(item)"><i class="ri-arrow-up-fill"></i></a>
                      <a v-tooltip="'Serial Down'" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit" @click="serialDown(item)"><i class="ri-arrow-down-fill"></i></a>
                    </td>
                    <td class="text-center">
                      <b-form-checkbox v-if="has_permission('permitted')" @change="toggleActiveStatus(item)" v-model="item.active" name="check-button" switch>
                      </b-form-checkbox>
                    </td>
                    <td class="text-center">
                      <a v-tooltip="'Edit'" v-if="has_permission('permitted')" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit" @click="editData(item)"><i class="ri-pencil-fill"></i></a>
                      <a v-tooltip="'Delete'" v-if="has_permission('permitted')" @click="deleteData(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
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
    <b-modal id="modal-1" ref="editModal" size="lg" title="Statuses" centered :hide-footer="true">
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

export default {
  components: {
    Form
  },
  data () {
    return {
      // pagination
      rows: 100,
      currentPage: 1,
      // form data
      search: {
        name: '',
        code: '',
        status_group_id: ''
      },
      value: '',
      listData: [],
      loading: false,
      editItem: ''
    }
  },
  computed: {
  },
  created () {
    if (this.$route.params.status_group_id) {
      this.search.status_group_id = this.hash_id(this.$route.params.status_group_id, false)[0]
      this.getListData()
    }
  },
  mounted () {
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
        code: '',
        status_group_id: this.search.status_group_id
      }
      this.getListData()
    },
    async getListData () {
      this.loading = true
      const params = Object.assign(this.search, { page: this.pagination.currentPage, per_page: this.pagination.perPage })
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_statuses_list', params)
      if (result.success) {
        this.listData = result.data.data
        this.paginationData(result.data)
      }
      this.loading = false
    },
    async toggleActiveStatus (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/toggle_status_active_status', item)
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
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_status_data', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getListData()
        this.getCommonDropdownList()
      }
    },
    async serialUp (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/status_serial_up', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getListData()
        this.getCommonDropdownList()
      } else {
        this.$toast.error({
          title: 'Failed',
          message: result.message
        })
      }
    },
    async serialDown (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/status_serial_down', item)
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
