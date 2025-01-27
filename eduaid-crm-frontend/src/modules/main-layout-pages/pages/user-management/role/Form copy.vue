<template>
  <b-overlay :show="loading">
    <b-breadcrumb>
      <b-breadcrumb-item to="/dashboard">
        <b-icon icon="house-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
        Dashboard
      </b-breadcrumb-item>
      <!-- <b-breadcrumb-item to="/user">Manage Book</b-breadcrumb-item> -->
      <b-breadcrumb-item to="/role">Role</b-breadcrumb-item>
      <b-breadcrumb-item active>Add New Role</b-breadcrumb-item>
    </b-breadcrumb>
    <div class="formBoder">
      <h4 class="header">Add Role</h4>
      <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
        <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
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
                Name <span>*</span>
              </template>
                <b-form-input
                  id="name"
                  v-model="form.name"
                  type="text"
                  placeholder="Enter Name"
                  :state="errors[0] ? false : (valid ? true : null)"
                ></b-form-input>
                <div class="invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="6" md="6" sm="6" xs="12">
            <ValidationProvider name="Code" vid="code" rules="required" v-slot="{ errors }">
              <b-form-group
                id="name"
                label="Name"
                label-for="name"
              >
              <template v-slot:label>
                Code <span>*</span>
              </template>
                <b-form-input
                  id="code"
                  v-model="form.code"
                  type="text"
                  placeholder="Enter Code"
                  :state="errors[0] ? false : (valid ? true : null)"
                ></b-form-input>
                <div class="invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="6" md="6" sm="6" xs="12">
            <ValidationProvider name="Type" vid="type" rules="required">
              <b-form-group
                  label-for="type"
                  slot-scope="{ valid, errors }"
              >
              <template v-slot:label>
                Type <span>*</span>
              </template>
              <b-form-select
                  plain
                  v-model="form.type"
                  :options="typeList"
                  id="type"
                  :state="errors[0] ? false : (valid ? true : null)"
              >
                  <template v-slot:first>
                  <b-form-select-option :value=null>Select</b-form-select-option>
                  </template>
              </b-form-select>
              <div class="invalid-feedback">
                  {{ errors[0] }}
              </div>
              </b-form-group>
          </ValidationProvider>
          </b-col>
        </b-row>
        <div class="row mt-3">
          <div class="col-sm-3"></div>
          <div class="col text-right">
              <b-button type="submit" variant="primary" class="mr-2">{{ SaveButton }}</b-button>
              &nbsp;
              <b-button variant="danger" class="mr-1" @click="$bvModal.hide('modal-1')">Cancel</b-button>
          </div>
        </div>
      </b-form>
      </ValidationObserver>
    </div>
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
        type: null,
        code: ''
      },
      errors: [],
      valid: null,
      loading: false,
      parentList: []
    }
  },
  watch: {
    'form.name': function (name) {
      const nameLowerCase = name.toLowerCase()
      const roleCode = nameLowerCase.replaceAll(' ', '_')
      this.form.code = roleCode
    }
  },
  created () {
    if (this.editItem) {
      this.form = this.editItem
    }
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
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_role_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_role_data', this.form)
      }
      if (result.success) {
        this.$emit('loadList', true)
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.$bvModal.hide('modal-1')
        this.loading = false
      } else {
        this.$refs.form.setErrors(result.errors)
      }
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
    margin: -17px -2px -11px 4px;
 }
</style>
