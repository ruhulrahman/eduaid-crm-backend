<template>
  <div class="section-wrapper">
    <b-breadcrumb>
      <b-breadcrumb-item to="/dashboard">
        <b-icon icon="house-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
        Dashboard
      </b-breadcrumb-item>
      <b-breadcrumb-item active>User Management</b-breadcrumb-item>
      <b-breadcrumb-item active>User</b-breadcrumb-item>
    </b-breadcrumb>
      <div class="form-wrapper">
      <b-card title="Search">
          <b-card-text>
            <b-row>
                <b-col sm="12" md="3">
                  <b-form-group
                      id="name"
                      label="Name"
                      label-for="name"
                  >
                      <b-form-input
                      id="name"
                      v-model="search.name"
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
                      id="email"
                      v-model="search.email"
                      placeholder="Enter Email"
                      required
                      ></b-form-input>
                  </b-form-group>
                </b-col>
                <b-col sm="12" md="3">
                  <b-form-group
                      id="phone"
                      label="Phone"
                      label-for="Phone"
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
            <h4 class="card-title mb-0 pl-0">Users List</h4>
          </b-col>
          <b-col class="text-right">
            <b-button v-if="has_permission('add_new_user')" size="sm" variant="primary" @click="openAddNewModal()">Add New<i class="ri-add-fill"></i></b-button>
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
                    <th scope="col" class="text-center">Email</th>
                    <th scope="col" class="text-center">Phone</th>
                    <th scope="col" class="text-center">Role</th>
                    <!-- <th scope="col" class="text-center">User Type</th> -->
                    <th scope="col" class="text-center">Active</th>
                    <th scope="col" class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody v-for="(item, index) in listData" :key="index">
                  <tr style="font-size: 12px;">
                    <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                    <td class="text-left">{{ item.name }}</td>
                    <td class="text-left">{{ item.email }}</td>
                    <td class="text-left">{{ item.phone }}</td>
                    <td class="text-center">{{ cn(item, 'role.name', '') }}</td>
                    <!-- <td class="text-center">{{ item.user_type }}</td> -->
                    <td class="text-center">
                      <b-form-checkbox :disabled="!has_permission('active_or_deactive_user')" @change="toggleActiveStatus(item)" v-model="item.active" name="check-button" switch>
                        <!-- <span class="badge badge-pill badge-success" v-if="item.active">Active</span>
                        <span class="badge badge-pill badge-danger" v-else>InActive</span> -->
                      </b-form-checkbox>
                    </td>
                    <td class="text-center">
                      <a v-tooltip="'Edit'" v-if="has_permission('edit_user')" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit" @click="editData(item)"><i class="ri-pencil-fill"></i></a>
                      <a v-tooltip="'Delete'" v-if="has_permission('delete_user')" @click="deleteConfirmation(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
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
    <b-modal id="modal-1" ref="editModal" size="lg" title="User Entry" :hide-footer="true">
      <Form @loadList="loadData" :editItem="editItem"/>
    </b-modal>
     <!-- pagination -->
     <div class="pagination-wrapper mt-4">
      <span>Showing {{ pagination.totalSeen }} from {{ pagination.totalRows }} entries</span>
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
        email: '',
        phone: ''
      },
      value: '',
      listData: [],
      loading: false,
      editItem: '',
      roleList: []
    }
  },
  created () {
    this.loadData()
  },
  mounted () {
    this.getRoleList()
  },
  computed: {
    formTitle () {
      return (this.editItem === 0) ? 'Role Entry' : 'Role Modify'
    }
  },
  methods: {
    openAddNewModal () {
      this.editItem = ''
      this.$refs.editModal.show()
    },
    editData (item) {
      this.editItem = item
      this.$refs.editModal.show()
    },
    searchData () {
      this.loadData()
    },
    clearData () {
      this.search = {
        name: '',
        email: '',
        phone: ''
      }
      this.loadData()
    },
    async loadData () {
      this.loading = true
      const params = Object.assign({}, this.search, { page: this.pagination.currentPage, per_page: this.pagination.perPage })
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_user_list', params)
      this.listData = result.data.data
      this.paginationData(result.data)
      this.loading = false
    },
    async toggleActiveStatus (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/toggle_user_status', item)
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.loadData()
      }
      this.loading = false
    },
    deleteConfirmation (item) {
      this.$swal({
        title: 'Are you sure to delete?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        focusConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          // declare confirmed method to hit api
          this.deleteRole(item)
        }
      })
    },
    async deleteRole (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_user_data', item)
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.loadData()
      }
      this.loading = false
    },
    getRoleList () {
      RestApi.getData(baseURL, 'api/v1/admin/ajax/get_all_role_list', null).then(response => {
        if (response.success) {
          var data = response.data
          this.roleList = data.filter(obj => obj.value !== null && obj.text !== null) // Exclude items with null values
            .map((obj, index) => {
              return { value: obj.value, text: obj.text }
            })
        } else {
          this.roleList = []
        }
      })
    },
    getRoleName (id) {
      const Obj = this.roleList.find(el => el.value === id)
      if (Obj !== undefined) {
        return Obj.text
      } else {
        return ''
      }
    }
  }
}
</script>
