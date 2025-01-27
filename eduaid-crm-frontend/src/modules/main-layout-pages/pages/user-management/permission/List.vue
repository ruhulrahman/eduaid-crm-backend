<template>
  <div class="section-wrapper">
    <b-breadcrumb>
      <b-breadcrumb-item to="/dashboard">
        <b-icon icon="house-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
        Dashboard
      </b-breadcrumb-item>
      <b-breadcrumb-item active>User Management</b-breadcrumb-item>
      <b-breadcrumb-item active>Permission</b-breadcrumb-item>
    </b-breadcrumb>
      <div class="form-wrapper">
      <b-card title="Permission Search">
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
                      id="code"
                      label="Code"
                      label-for="Code"
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
                  <b-form-group
                      id="type"
                      label="Type"
                      label-for="Type"
                  >
                      <b-form-select
                      id="type"
                      v-model="search.type"
                      :options="typeList"
                      placeholder="Enter Type"
                      >
                      <template v-slot:first>
                        <b-form-select-option :value=null>Select</b-form-select-option>
                      </template>
                    </b-form-select>
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
            <h4 class="card-title mb-0 pl-0">Permission List</h4>
          </b-col>
          <b-col class="text-right">
            <b-button size="sm" variant="primary" @click="openAddNewModal()"><i class="ri-add-fill"></i>Add New Permission</b-button>
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
                      <th scope="col" class="text-center">Type</th>
                      <th scope="col" class="text-center">Name</th>
                      <th scope="col" class="text-center">Code</th>
                      <th scope="col" class="text-center">Parent</th>
                      <th scope="col" class="text-center">Active</th>
                      <th scope="col" class="text-center">Action</th>
                    </tr>
                  </thead>
                  <template v-if="listData.length">
                    <tbody v-for="(item, index) in listData" :key="index">
                      <tr style="font-size: 12px;">
                        <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                        <td class="text-center" style="text-transform:capitalize">{{ item.type }}</td>
                        <td class="text-left">{{ item.name }}</td>
                        <td class="text-left">{{ item.code }}</td>
                        <td class="text-center">
                          <b-badge pill variant="success" v-if="item.parent"> {{ item.parent.name }}</b-badge>
                        </td>
                        <td class="text-center">
                          <b-form-checkbox @change="toggleActiveStatus(item)" v-model="item.active" name="check-button" switch>
                            <!-- <span class="badge badge-pill badge-success" v-if="item.active">Active</span>
                            <span class="badge badge-pill badge-danger" v-else>InActive</span> -->
                          </b-form-checkbox>
                        </td>
                        <td class="text-center">
                          <a v-tooltip="'Edit'" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn edit" @click="editData(item)"><i class="ri-pencil-fill"></i></a>
                          <a v-tooltip="'Delete'" @click="deleteConfirmation(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                        </td>
                      </tr>
                    </tbody>
                  </template>
                  <template v-else>
                      <tr>
                        <td colspan="12" class="notFound">Data Not Found</td>
                      </tr>
                  </template>
                </table>
              </div>
            </b-card>
          </b-overlay>
        </b-col>
     </b-row>
  </b-card>
    <b-modal id="modal-1" ref="editModal" size="md" title="Permission Form" :hide-footer="true">
      <Form @loadList="loadData" :editItem="editItem"/>
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
// import { permissionList } from '../../../api/routes'
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
        type: null
      },
      value: '',
      listData: [],
      loading: false,
      editItem: '',
      parentList: []
    }
  },
  watch: {
    'search.name': function (oldValue, newValue) {
      this.searchData()
    },
    'search.code': function (oldValue, newValue) {
      this.searchData()
    },
    'search.type': function (oldValue, newValue) {
      this.searchData()
    }
  },
  created () {
    this.loadData()
  },
  computed: {
    typeList: function () {
      const list = [
        { value: 'Page', text: 'Page' },
        { value: 'Feature', text: 'Feature' }
      ]
      return list
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
        code: '',
        type: ''
      }
      this.loadData()
    },
    async loadData () {
      this.loading = true
      const params = Object.assign({}, this.search, { page: this.pagination.currentPage, per_page: this.pagination.perPage })
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_permission_list', params)
      if (result.success) {
        this.listData = result.data.data
        this.paginationData(result.data)
      }
      this.loading = false
    },
    async toggleActiveStatus (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/toggle_permission_active_status', item)
      if (result.success) {
        this.$toast.success({ title: 'Success', message: result.message })
        this.loadData()
      }
      this.loading = false
    },
    parentListDropDown () {
      RestApi.getData(baseURL, 'api/v1/admin/ajax/get_permission_parent_list', null).then(response => {
        if (response.success) {
          var data = response.data
          this.parentList = data.filter(obj => obj.value !== null && obj.text !== null) // Exclude items with null values
            .map((obj, index) => {
              return { value: obj.value, text: obj.text }
            })
        } else {
          this.parentList = []
        }
      })
    },
    getParentName (id) {
      const Obj = this.parentList.find(el => el.value === id)
      if (Obj !== undefined) {
        return Obj.text
      } else {
        return ''
      }
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
          this.deletePermission(item)
        }
      })
    },
    async deletePermission (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_permission_data', item)
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.loadData()
      }
      this.loading = false
    }
  }
}
</script>
<style>
 .notFound {
   text-align: center;
 }
</style>
