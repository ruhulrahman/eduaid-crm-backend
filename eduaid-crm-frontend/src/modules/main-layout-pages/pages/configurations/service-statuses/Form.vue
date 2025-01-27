<template>
  <b-overlay :show="loading">
    <div class="formBoder">
    <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
      <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
        <b-row>
        <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Country" vid="country_id" rules="required" v-slot="{ errors }">
            <b-form-group
                class="row"
                label-cols-sm="12"
                label-for="country_id"
              >
              <template v-slot:label>
                Country <span class="text-danger">*</span>
              </template>
              <treeselect
                id="country_id"
                :options="serviceWishCountryList"
                placeholder="Select Country"
                v-model="form.country_id"
                :state="errors[0] ? false : (valid ? true : null)"
                />
              <template v-slot:first>
                <b-form-select-option :id=0>Select</b-form-select-option>
              </template>
            <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Service" vid="service_id" rules="required" v-slot="{ errors }">
            <b-form-group
                class="row"
                label-cols-sm="12"
                label-for="service_id"
              >
              <template v-slot:label>
                Service <span class="text-danger">*</span>
              </template>
              <treeselect
                id="service_id"
                :options="serviceListFiltered"
                placeholder="Select Service"
                v-model="form.service_id"
                :state="errors[0] ? false : (valid ? true : null)"
                />
              <template v-slot:first>
                <b-form-select-option :id=0>Select</b-form-select-option>
              </template>
            <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Name" vid="name" rules="required" v-slot="{ errors }">
            <b-form-group
              class="row"
              label-cols-sm="12"
              label-for="name"
            >
            <template v-slot:label>
              Name <span class="text-danger">*</span>
            </template>
              <b-form-input
                id="name"
                v-model="form.name"
                placeholder="Enter Name"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Color Code" vid="color_code" rules="required" v-slot="{ errors }">
            <b-form-group
              class="row"
              label-cols-sm="12"
              label-for="color_code"
            >
            <template v-slot:label>
              Color Code <span class="text-danger">*</span>
            </template>
            <b-input-group>
              <b-form-input
                id="color_code"
                type="color"
                v-model="form.color_code"
                placeholder="Enter Color Code"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
            </b-input-group>
              <div class="d-block invalid-feedback">
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
// import the component
import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
  props: ['editItem', 'serviceList', 'countryList'],
  components: {
    Treeselect
  },
  data () {
    return {
      SaveButton: this.editItem ? 'Update' : 'Save',
      form: {
        country_id: null,
        service_id: null,
        name: '',
        code: '',
        color_code: '#000000',
        serial: '',
        active: 1,
      },
      errors: [],
      valid: null,
      loading: false
    }
  },
  computed: {
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
      if (this.form.country_id) {
        return this.serviceList.filter(item => item.country_id === this.form.country_id)
      } else {
        return this.serviceList
      }
    }
  },
  watch: {
    'form.name': function (newVal) {
      const nameLowerCase = newVal.toLowerCase()
      const permisCode = nameLowerCase.replaceAll(' ', '_')
      this.form.code = permisCode
    }
  },
  created () {
    if (this.editItem) {
      this.form = this.editItem
    }
  },
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_service_status_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_service_status_data', this.form)
      }
      this.loading = false
      if (result.success) {
        this.$emit('reloadList', true)
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.$bvModal.hide('modal-1')
        this.getCommonDropdownList()
      } else {
        this.$refs.form.setErrors(result.errors)
      }
    },
    addNewBreakdown: function () {
      this.form.breakdowns.push({
        value: '',
        point: 0
      })
    },
    removeBreakdown: function (index) {
      this.form.breakdowns.splice(index, 1)
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
 .input-group-text {
   height: 30.5px!important;
 }
</style>
