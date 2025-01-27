<template>
  <b-overlay :show="loading">
    <b-breadcrumb>
      <b-breadcrumb-item to="/dashboard">
        <b-icon icon="house-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
        Dashboard
      </b-breadcrumb-item>
      <b-breadcrumb-item active>User Management</b-breadcrumb-item>
      <b-breadcrumb-item to="/role">Role</b-breadcrumb-item>
      <b-breadcrumb-item active>{{ form.id ? 'Update' : 'Add New' }} Role Permission</b-breadcrumb-item>
    </b-breadcrumb>
    <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
      <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
        <b-card>
          <h4 class="header">Add Role</h4>
          <hr class="text-light">
          <b-row>
            <b-col lg="6" md="6" sm="6" xs="12">
              <ValidationProvider name="Name" vid="name" rules="required" v-slot="{ errors }">
                <b-form-group
                  id="name"
                  label="Name"
                  label-for="name"
                >
                <template v-slot:label>
                  Role Name <span>*</span>
                </template>
                  <b-form-input
                    id="name"
                    v-model="form.name"
                    type="text"
                    placeholder="Enter role name"
                    :state="errors[0] ? false : (valid ? true : null)"
                  ></b-form-input>
                  <div class="invalid-feedback">
                    {{ errors[0] }}
                  </div>
                </b-form-group>
              </ValidationProvider>
            </b-col>
          </b-row>
        </b-card>

        <div v-for="(item, index) in permissionList" :key="index">
          <b-card class="mt-4">
            <b-row class="bg-label-success">
              <b-col>
                <template>
                  <div>
                    <b-form-checkbox
                      :id="'checkbox-'+index"
                      :value="item.id"
                      v-model="form.role_permission_ids"
                    >
                     <strong v-html="item.name"></strong>
                    </b-form-checkbox>
                  </div>
                </template>
              </b-col>
              <b-col class="text-right">
                <template>
                  <div>
                    <b-form-checkbox
                      :id="'selectAll-'+index"
                      v-model="item.checkAll"
                      :value="true"
                      :unchecked-value="false"
                      @change="selectAllPermissionDownThisRow(item.checkAll, index)"
                    >
                    Select All
                    </b-form-checkbox>
                  </div>
                </template>
              </b-col>
            </b-row>

            <b-row>
              <b-col>
                <hr  v-if="item.children_pages.length">
                <h4 class="header" v-if="item.children_pages.length">Page</h4>
                <b-row v-if="item.children_pages.length" style="margin-left: 10px;">
                  <b-col class="mt-3" lg="3" md="3" sm="3" xs="6" v-for="(childPage, childPageIndex) in item.children_pages" :key="childPageIndex">
                    <b-form-checkbox
                          :id="'page-'+index+childPageIndex"
                          v-model="form.role_permission_ids"
                          :value="childPage.id"
                        >
                        {{ childPage.name }}
                        </b-form-checkbox>
                  </b-col>
                </b-row>

                <hr v-if="item.children_operations.length">
                <h4 v-if="item.children_operations.length" class="header">Operation</h4>
                <b-row v-if="item.children_operations.length" style="margin-left: 10px;">
                  <b-col class="mt-3" lg="3" md="3" sm="3" xs="6" v-for="(childOperation, childOperationIndex) in item.children_operations" :key="childOperationIndex">
                      <b-form-checkbox
                        :id="'operation-'+index+childOperationIndex"
                        v-model="form.role_permission_ids"
                        :value="childOperation.id"
                      >
                      {{ childOperation.name }}
                      </b-form-checkbox>
                  </b-col>
                </b-row>

              </b-col>
            </b-row>
          </b-card>
        </div>

{{ form.role_permission_ids }}
        <div class="row mt-3 mb-3">
          <div class="col text-right">
              <b-button type="submit" pill variant="info" class="mr-2">Submit</b-button>
              <!-- <b-button type="submit" variant="success" btn btn-sm class="mr-2">Submit</b-button> -->
              &nbsp;
              <b-button variant="danger" pill class="mr-1" @click="goToRole()">Cancel</b-button>
          </div>
        </div>
      </b-form>
    </ValidationObserver>
  </b-overlay>
</template>
<script>
import RestApi, { baseURL } from '@/config'
// import { permissionStore } from '../../../api/routes'

export default {
  props: ['editItem'],
  data () {
    return {
      SaveButton: this.editItem ? 'Update' : 'Save',
      form: {
        name: '',
        type: 'Users',
        code: '',
        role_permission_ids: []
      },
      errors: [],
      valid: null,
      loading: false,
      parentList: [],
      permissionList: [],
      permissionData: [],
      parentName: []
    }
  },
  watch: {
    'form.name': function (name) {
      const nameLowerCase = name.toLowerCase()
      const roleCode = nameLowerCase.replaceAll(' ', '_')
      this.form.code = roleCode
    },
    'form.role_permission_ids': function (value) {
      // console.log('this.form.role_permission_ids', this.form.role_permission_ids)
    }
  },
  created () {
    this.getParentWisePermissionList()
    this.getParentIdWiseName()
    if (this.editItem) {
      this.form = this.editItem
    }
  },
  mounted () {
    const roleId = this.$route.params.role_id
    if (roleId) {
      this.getPermissionsByRole(roleId)
    }
    this.getPermissionList()
  },
  computed: {
    typeList: function () {
      const list = [
        { value: 'Users', text: 'Users' }
      ]
      return list
    }
  },
  methods: {
    selectAllPermissionDownThisRow (checkAll, index) {
      const permissionObj = this.permissionList.find((item, localIndex) => localIndex === index)
      if (checkAll) {
        if (permissionObj.children_pages.length) {
          permissionObj.children_pages.forEach(item => {
            this.form.role_permission_ids.push(item.id)
          })
        }
        if (permissionObj.children_operations.length) {
          permissionObj.children_operations.forEach(item => {
            this.form.role_permission_ids.push(item.id)
          })
        }
        this.form.role_permission_ids.push(permissionObj.id)
      } else {
        if (permissionObj.children_pages.length) {
          permissionObj.children_pages.forEach(item => {
            const itemIndex = this.form.role_permission_ids.indexOf(item.id)
            this.form.role_permission_ids.splice(itemIndex, 1)
          })
          // console.log('pagePermissionIds', pagePermissionIds)
        }
        if (permissionObj.children_operations.length) {
          permissionObj.children_operations.forEach(item => {
            const itemIndex = this.form.role_permission_ids.indexOf(item.id)
            this.form.role_permission_ids.splice(itemIndex, 1)
          })
        }
        const itemIndex = this.form.role_permission_ids.indexOf(permissionObj.id)
        this.form.role_permission_ids.splice(itemIndex, 1)
      }
    },
    goToRole () {
      this.$router.go(-1)
    },
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_role_permission_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_role_permission_data', this.form)
      }
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.$router.push('/role')
        this.loading = false
      } else {
        this.$refs.form.setErrors(result.errors)
      }
    },
    async getPermissionsByRole (roleId) {
      this.loading = true
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_permissions_by_role_id', { role_id: roleId })
      this.form = result.data
      this.loading = false
    },
    async getPermissionList () {
      this.loading = true
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_permission_parent_and_child_list', { role_id: this.$route.params.role_id })
      this.permissionList = result.data
      this.loading = false
    }
  }
}
</script>
<style>
  .formBoder {
    border: 1px solid;
    margin: 5px;
    padding: 35px;
    font-size: 13px
 }
 .header {
    font-family: auto;
    font-size: 19px;
    margin: -8px 0px -8px 0px;
 }
 .select {
    font-family: auto;
    font-size: 15px;
    margin: -8px 0px -8px 0px;
 }
</style>
