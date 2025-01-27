<template>
    <b-overlay :show="loading">
      <div class="formBoder">
      <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
        <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
          <b-row>
            <b-col lg="6" md="6" sm="12" xs="12">
              <ValidationProvider name="Country" vid="country_id" rules="required" v-slot="{ errors }">
                  <b-form-group
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
              <ValidationProvider name="Service Status" vid="service_status_id" rules="required" v-slot="{ errors }">
                  <b-form-group
                    label-for="service_status_id"
                  >
                  <template v-slot:label>
                    Service Status <span class="text-danger">*</span>
                  </template>
                  <treeselect
                    id="service_status_id"
                    :options="serviceStatusListFiltered"
                    placeholder="Select Service Status"
                    v-model="form.service_status_id"
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
  import '@riophae/vue-treeselect/dist/vue-treeselect.css'
  import flatPickr from 'vue-flatpickr-component'
  import 'flatpickr/dist/flatpickr.css'

  export default {
    props: ['leadDetails', 'editItem', 'dependentList',],
    components: {
      Treeselect,
      flatPickr
    },
    data () {
      return {
        SaveButton: this.editItem ? 'Update' : 'Save',
        form: {
          lead_id: '',
          country_id: null,
          service_id: null,
          service_status_id: null,
          active: true
        },
        errors: [],
        valid: null,
        loading: false
      }
    },
    created () {
      if (this.editItem) {
        this.form = JSON.parse(this.editItem)
      }
      this.form.lead_id = this.leadDetails.id
    },
    computed: {
      serviceList: function () {
        return this.cn(this.$store.state.dropdowns, 'services')
      },
      serviceStatusList: function () {
        return this.cn(this.$store.state.dropdowns, 'serviceStatuses')
      },
      // serviceStatusList: function () {
      //   const statusGroupList = this.cn(this.$store.state, 'dropdowns.status_groups')
      //   return this.getStatuseByGroupCode(statusGroupList, 'service_status')
      // },
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
        if (this.form.country_id) {
          return this.serviceList.filter(item => item.country_id === this.form.country_id)
        } else {
          return []
        }
      },
      serviceStatusListFiltered: function () {
        if (this.form.service_id) {
          return this.serviceStatusList.filter(item => item.service_id === this.form.service_id)
        } else {
          return []
        }
      }
    },
    watch: {
    },
    methods: {
      async submitData () {
        this.loading = true
        let result = ''
        if (this.form.id) {
          result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_lead_service_data', this.form)
        } else {
          result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_lead_service_data', this.form)
        }
        this.loading = false
        if (result.success) {
          this.$emit('reloadList', true)
          this.$emit('reloadLeadDetails', this.form.lead_id)
          this.$toast.success({
            title: 'Success',
            message: result.message
          })
          this.$bvModal.hide('modal-1')
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
   .input-group-text {
     height: 30.5px!important;
   }
  </style>
