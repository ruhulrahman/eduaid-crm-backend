<template>
  <b-overlay :show="loading">
    <div class="formBoder">
    <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
      <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
        <b-row>
        <b-col lg="4" md="4" sm="12" xs="12">
          <ValidationProvider name="First Name" vid="name" rules="required|alpha_spaces" v-slot="{ errors }">
            <b-form-group
              label-for="name"
            >
            <template v-slot:label>
              First Name <span class="text-danger">*</span>
            </template>
              <b-form-input
                id="name"
                v-model="form.name"
                placeholder="Enter First Name"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="4" md="4" sm="12" xs="12">
          <ValidationProvider name="Type" vid="type" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="type"
              >
              <template v-slot:label>
                Type <span class="text-danger">*</span>
              </template>
              <treeselect
                id="type"
                :options="dependentTypes"
                placeholder="Select Type"
                v-model="form.type"
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
        <b-col lg="4" md="4" sm="12" xs="12">
          <ValidationProvider name="Email" vid="email" rules="required|email" v-slot="{ errors }">
            <b-form-group
              label-for="email"
            >
            <template v-slot:label>
             Email <span class="text-danger">*</span>
            </template>
            <b-input-group>
                <b-form-input
                id="email"
                v-model="form.email"
                placeholder="Enter Email"
                :state="errors[0] ? false : (valid ? true : null)"
                ></b-form-input>
            </b-input-group>
              <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Mobile" vid="mobile" rules="required|numeric" v-slot="{ errors }">
            <b-form-group
              label-for="mobile"
            >
            <template v-slot:label>
              Mobile <span class="text-danger">*</span>
            </template>
            <b-input-group>

              <template #prepend>
                <treeselect
                style="width: 250px;"
                id="mobile_country_id"
                :options="mobileCountryCodeList"
                placeholder="Select Country Calling Code"
                v-model="form.mobile_country_id"
                :state="errors[0] ? false : (valid ? true : null)"
                />
              </template>
                <b-form-input
                id="mobile"
                v-model="form.mobile"
                placeholder="Enter Mobile (1724xxxxxxx)"
                :state="errors[0] ? false : (valid ? true : null)"
                ></b-form-input>
            </b-input-group>
              <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="4" md="4" sm="12" xs="12">
          <ValidationProvider name="Alternative Mobile" vid="alt_mobile" rules="numeric" v-slot="{ errors }">
            <b-form-group
              label-for="alt_mobile"
            >
            <template v-slot:label>
              Alternative Mobile
            </template>
            <b-input-group>
                <b-form-input
                id="alt_mobile"
                v-model="form.alt_mobile"
                placeholder="Enter Alternative Mobile (1724xxxxxxx)"
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
// import flatPickr from 'vue-flatpickr-component'
// import 'flatpickr/dist/flatpickr.css'

export default {
  props: ['leadDetails', 'editItem', 'userList', 'countryList'],
  components: {
    Treeselect
    // flatPickr
  },
  data () {
    return {
      SaveButton: this.editItem ? 'Update' : 'Save',
      form: {
        lead_id: '',
        name: '',
        type: null,
        email: '',
        mobile_country_id: null,
        mobile: '',
        alt_mobile: ''
      },
      flatPickrConfig: {
        // maxDate: 'today',
        dateFormat: 'd-m-Y'
      },
      errors: [],
      valid: null,
      loading: false
    }
  },
  computed: {
    mobileCountryCodeList: function () {
      return this.countryList.map(item => {
        return {
          id: item.id,
          label: `${item.name}(+${item.calling_code})`
        }
      })
    },
    dependentTypes: function () {
      return this.cn(this.$store.state.dropdowns, 'dependentTypes')
    }
  },
  watch: {
  },
  created () {
    if (this.editItem) {
      this.form = JSON.parse(this.editItem)
    }
    this.form.lead_id = this.leadDetails.id
  },
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_lead_dependent_info_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_lead_dependent_info_data', this.form)
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
