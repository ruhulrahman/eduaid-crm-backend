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
      <b-breadcrumb-item active>Control Notifications</b-breadcrumb-item>
    </b-breadcrumb>
      <div class="form-wrapper">
      <b-card title="Control Notification Search">
          <b-card-text>
              <b-row style="font-size: 12px;">
                <b-col lg="3" md="3" sm="3" xs="6">
                  <b-form-group
                    id="name"
                    label="Name"
                    label-for="name"
                  >
                    <b-form-input
                      id="name"
                      v-model="search.name"
                      placeholder="Enter Name"
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
          <h4 class="card-title mb-0 pl-0">Control Notifications List</h4>
        </b-col>
        <b-col class="text-right">
          <!-- <b-button v-if="has_permission('add_new_vat_percent')" size="sm" variant="info" @click="openAddNewModal()">Add New<i class="ri-add-fill"></i></b-button> -->
        </b-col>
      </b-row>
    </b-card-title>
      <b-row>
      <b-col>
        <b-overlay :show="loading">
            <div class="table-wrapper table-responsive">
              <table class="table table-striped table-hover table-bordered">
                <thead>
                  <tr style="font-size: 12px;">
                    <th scope="col" class="text-center">SL</th>
                    <th scope="col" class="text-center">name</th>
                    <th scope="col" class="text-center">Active</th>
                    <!-- <th scope="col" class="text-center">Action</th> -->
                  </tr>
                </thead>
                <template v-if="listData.length > 0">
                  <tbody v-for="(item, index) in listData" :key="index">
                    <tr style="font-size: 11px;">
                      <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                      <td class="text-center">{{ item.name }}</td>
                      <td class="text-center">
                      <b-form-checkbox :disabled="!has_permission('active_or_deactive_control_notification')" @change="toggleActiveStatus(item)" v-model="item.active" name="check-button" switch>
                        </b-form-checkbox>
                      </td>
                      <!-- <td class="text-center">
                        <a v-tooltip="'Edit'" v-if="has_permission('edit_vat_percent')" href="javascript:" style="width: 20px !important; height: 20px !important; font-size:10px" class="action-btn edit" @click="editData(item)"><i class="ri-pencil-fill"></i></a>
                        <a v-tooltip="'Delete'" v-if="has_permission('delete_vat_percent')" @click="deleteConfirmation(item)" style="width: 20px !important; height: 20px !important; font-size:10px" href="javascript:" class="action-btn delete"><i class="ri-delete-bin-2-line"></i></a>
                      </td> -->
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
        </b-overlay>
      </b-col>
    </b-row>
  </b-card>
    <b-modal id="modal-1" ref="editModal" size="md" title="VAT Percent" centered :hide-footer="true">
      <Form @loadList="getListData" :editItem="editItem"/>
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
        code: ''
      },
      value: '',
      listData: [],
      loading: false,
      editItem: '',
      flatPickrConfig: {
        maxDate: 'today',
        dateFormat: 'd-m-Y'
      }
    }
  },
  created () {
    this.getListData()
  },
  mounted () {
  },
  computed: {
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
      this.getListData()
    },
    clearData () {
      this.search = {
        vat_percent: '',
        start_date: '',
        end_date: ''
      }
      this.getListData()
    },
    async getListData () {
      this.loading = true
      const params = Object.assign({}, this.search, { page: this.pagination.currentPage, per_page: this.pagination.perPage })
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_control_notification_list', params)
      if (result.success) {
        this.listData = result.data.data
        this.paginationData(result.data)
      }
      this.loading = false
    },
    async toggleActiveStatus (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/toggle_control_notification_data', item)
      if (result.success) {
        this.$toast.success({ title: 'Success', message: result.message })
        this.getListData()
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
          this.deleteRole(item)
        }
      })
    },
    async deleteRole (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_vat_percent_data', item)
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.getListData()
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
