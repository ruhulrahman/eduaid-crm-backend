<template>
  <b-overlay :show="loading">
    <div class="formBoder">
    <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
      <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
        <b-row>
          <b-col v-if="dependentList.length" lg="4" md="4" sm="12" xs="12">
            <ValidationProvider name="Dependent" vid="dependent_id" rules="" v-slot="{ errors }">
                <b-form-group
                  label-for="dependent_id"
                >
                <template v-slot:label>
                  Dependent <span class="text-danger"></span>
                </template>
                <treeselect
                  id="dependent_id"
                  :options="dependentList"
                  placeholder="Select Dependent"
                  v-model="form.dependent_id"
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
            <ValidationProvider name="Social Media" vid="social_id" rules="required" v-slot="{ errors }">
                <b-form-group
                  label-for="social_id"
                >
                <template v-slot:label>
                  Social Media <span class="text-danger">*</span>
                </template>
                <treeselect
                  id="social_id"
                  :options="socialList"
                  placeholder="Select Social Media"
                  v-model="form.social_id"
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
            <ValidationProvider name="Social Url" vid="social_url" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="social_url"
              >
              <template v-slot:label>
              Social Url <span class="text-danger">*</span>
              </template>
              <b-input-group>
                  <b-form-input
                  id="social_url"
                  type="url"
                  v-model="form.social_url"
                  placeholder="Enter social url"
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
  props: ['leadDetails', 'editItem', 'userList', 'countryList', 'dependentList'],
  components: {
    Treeselect
    // flatPickr
  },
  data () {
    return {
      SaveButton: this.editItem ? 'Update' : 'Save',
      form: {
        lead_id: '',
        dependent_id: null,
        social_id: null,
        social_url: '',
        active_: true
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
    socialList: function () {
      return this.cn(this.$store.state.dropdowns, 'socials')
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
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_lead_social_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_lead_social_data', this.form)
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
