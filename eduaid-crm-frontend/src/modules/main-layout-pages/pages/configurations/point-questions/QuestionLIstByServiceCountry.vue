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
      <b-breadcrumb-item to="/point-questions">Point Question</b-breadcrumb-item>
      <b-breadcrumb-item active>Point Question List By Country and Service</b-breadcrumb-item>
    </b-breadcrumb>

    <!-- <div class="form-wrapper">
        <b-card title="Point Question Search">
            <b-card-text>
                <b-row style="font-size: 12px;">
                  <b-col sm="12" md="3">
                    <b-form-group
                        label="Country"
                        label-for="country_id"
                    >
                    <treeselect
                      id="country_id"
                      :options="serviceWishCountryList"
                      placeholder="Select Country"
                      v-model="search.country_id"
                      />
                    </b-form-group>
                  </b-col>
                  <b-col sm="12" md="3">
                    <b-form-group
                        label="Service"
                        label-for="service_id"
                    >
                    <treeselect
                      id="service_id"
                      :options="serviceListFiltered"
                      placeholder="Select Service"
                      v-model="search.service_id"
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
    </div> -->

    <b-card class="mt-3">
      <b-card-title>
        <b-row>
          <b-col>
            <h4 class="card-title mb-0 pl-0">Point Question List</h4>
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
              <b-row class="p-2" v-if="service">
                <b-col md="3" v-if="service.country">
                  <strong>Country:</strong> <span class="badge badge-secondary badge-pill" v-html="service.country.name"></span>
                </b-col>
                <b-col md="3">
                  <strong>Service:</strong> <span class="badge badge-secondary badge-pill" v-html="service.name"></span>
                </b-col>
              </b-row>
              <div class="table-wrapper table-responsive mt-0">
                <table class="table table-striped table-hover table-bordered">
                  <thead>
                    <tr style="font-size: 12px;">
                      <th scope="col" class="text-center">SL</th>
                      <th scope="col" class="text-center">Question</th>
                      <th scope="col" class="text-center">Question Point Breakdowns</th>
                      <th scope="col" class="text-center">Active</th>
                      <th scope="col" class="text-center">Action</th>
                    </tr>
                  </thead>
                <template v-if="Object.keys(listData || { }).length">
                  <tbody v-for="(item, index) in listData" :key="index">
                    <tr style="font-size: 12px;">
                      <td scope="row" class="text-center">{{ index + pagination.slOffset }}</td>
                      <td class="text-left">
                        <p>{{ item.question }}</p>
                        <p>
                          <strong>Total Weight: </strong><span v-html="item.total_weight"></span>,
                          <strong> Pass Mark: </strong><span v-html="item.pass_mark"></span>
                        </p>
                      </td>
                      <td class="text-left">
                        <b-row v-for="(breakdown, index) in item.breakdowns" :key="index">
                          <b-col class="text-right">
                            <strong v-html="breakdown.value"></strong>
                          </b-col>
                          <b-col>
                            <strong> Point: </strong><span v-html="breakdown.point"></span>
                          </b-col>
                        </b-row>
                      </td>
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
                </template>
                <template v-else>
                  <tr>
                    <td class="text-center" colspan="5">No Data Found</td>
                  </tr>
                </template>
                </table>
              </div>
            </b-card>
          </b-overlay>
        </b-col>
      </b-row>
    </b-card>

    <b-modal id="modal-1" ref="editModal" size="lg" title="Point Question" centered :hide-footer="true">
      <Form @reloadList="searchData"
      :serviceList="serviceList"
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
        question: '',
        // country_id: null,
        // service_id: null
      },
      value: '',
      serviceId: '',
      service: '',
      listData: [],
      parentList: [],
      userList: [],
      loading: false,
      editItem: {}
    }
  },
  computed: {
    serviceList: function () {
      return this.cn(this.$store.state.dropdowns, 'services')
    },
    countryList: function () {
      return this.cn(this.$store.state.dropdowns, 'countries')
    },
    serviceWishCountryList: function () {
      let fileterCountryList = []
      if (this.serviceList.length) {
        this.serviceList.forEach(item => {
          const countryObj = this.countryList.find(country => country.id === item.country_id)
          fileterCountryList.push(countryObj)
        })
      } else {
        fileterCountryList = this.countryList
      }
      return fileterCountryList
    },
    serviceListFiltered: function () {
      if (this.search.country_id) {
        return this.serviceList.filter(item => item.country_id === this.search.country_id)
      } else {
        return this.serviceList
      }
    }
  },
  created () {
    // this.getListData()
    this.serviceId = this.hash_id(this.$route.params.service_id, false)[0]
    if (this.serviceId) {
      this.getPointQuestionListByService(this.serviceId)
    } else {
      this.$router.push(`/point-questions`)
    }
  },
  mounted () {
  },
  methods: {
    openAddNewModal () {
      // this.editItem = ''
      const form = {
        question: '',
        service_id: this.service.id,
        country_id: this.service.country_id,
        total_weight: 0,
        pass_mark: 0,
        active: 1,
        breakdowns: [
          {
            value: '',
            point: 0
          }
        ]
      }

      this.editItem = JSON.stringify(form)
      this.$refs.editModal.show()
    },
    editData (item) {
      this.editItem = JSON.stringify(item)
      this.$refs.editModal.show()
    },
    searchData () {
      // this.getListData()
      this.getPointQuestionListByService(this.serviceId)
    },
    // clearData () {
    //   this.search = {
    //     name: '',
    //     description: '',
    //     parent_id: null,
    //     country_id: null,
    //     account_id: null
    //   }
    //   this.getListData()
    // },
    // async getListData () {
    //   this.loading = true
    //   const params = Object.assign({}, this.search, { page: this.pagination.currentPage, per_page: this.pagination.perPage })
    //   var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_point_question_list', params)
    //   if (result.success) {
    //     this.listData = result.data.data
    //     this.paginationData(result.data)
    //   }
    //   this.loading = false
    // },
    async getPointQuestionListByService (serviceId) {
      this.loading = true
      const params = Object.assign({ service_id: serviceId }, this.search, { page: this.pagination.currentPage, per_page: this.pagination.perPage })
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_point_question_list_by_service_id', params)
      if (result.success) {
        this.service = result.service
        this.listData = result.data.data
        this.paginationData(result.data)
      }
      this.loading = false
    },
    async toggleActiveStatus (item) {
      this.loading = true
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/toggle_point_question_active_status', item)
      this.loading = false
      if (result.success) {
        this.$toast.success({ title: 'Success', message: result.message })
        // this.getListData()
        this.getPointQuestionListByService(this.serviceId)
        // this.getCommonDropdownList()
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
      var result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/delete_point_question_data', item)
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        // this.getListData()
        this.getPointQuestionListByService(this.serviceId)
      }
      this.loading = false
    }
  }
}
</script>
