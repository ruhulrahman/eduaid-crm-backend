<template>
  <b-overlay :show="loading">
    <div class="formBoder">
      <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
        <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
        <b-row>
          <b-col lg="12" md="12" sm="12" xs="12">
            <ValidationProvider name="Name" vid="name" rules="required" v-slot="{ errors }">
              <b-form-group
                id="name"
                label="Name"
                label-for="name"
              >
              <template v-slot:label>
                Name <span class="text-danger">*</span>
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
          <b-col v-if="!form.id" lg="12" md="12" sm="12" xs="12">
            <ValidationProvider name="Code" vid="code" v-slot="{ errors }">
              <b-form-group
                id="code"
                label="code"
                label-for="code"
              >
              <template v-slot:label>
               Permission Code
              </template>
                <b-form-input
                  id="code"
                  v-model="form.code"
                  placeholder="Enter Permit code"
                  :state="errors[0] ? false : (valid ? true : null)"
                ></b-form-input>
                <div class="invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="12" md="12" sm="12" xs="12">
            <ValidationProvider name="Type" vid="type" rules="required">
              <b-form-group
                  label-for="type"
                  slot-scope="{ valid, errors }"
              >
              <template v-slot:label>
                Type <span class="text-danger">*</span>
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
          <b-col lg="12" md="12" sm="12" xs="12">
            <ValidationProvider name="Parent" vid="parent_id">
              <b-form-group
                label-for="parent_id"
                slot-scope="{ valid, errors }"
              >
              <template v-slot:label>
                Parent
              </template>
              <b-form-select
                plain
                v-model="form.parent_id"
                :options="parentList"
                id="parent_id"
                :state="errors[0] ? false : (valid ? true : null)"
              >
              <template v-slot:first>
                <b-form-select-option :value=null>Select parent</b-form-select-option>
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
              <b-button type="submit" variant="primary" class="mr-2">{{ saveBtn }}</b-button>
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
      saveBtn: this.editItem ? 'Update' : 'Save',
      form: {
        name: '',
        type: null,
        code: null,
        parent_id: null
      },
      errors: [],
      valid: null,
      loading: false,
      parentList: []
    }
  },
  created () {
    this.parentListDropDown()
    if (this.editItem) {
      this.form = this.editItem
    }
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
  watch: {
    'form.name': function (newVal) {
      const nameLowerCase = newVal.toLowerCase()
      const permisCode = nameLowerCase.replaceAll(' ', '_')
      this.form.code = permisCode
    }
  },
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_permission_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_permission_data', this.form)
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
</style>
