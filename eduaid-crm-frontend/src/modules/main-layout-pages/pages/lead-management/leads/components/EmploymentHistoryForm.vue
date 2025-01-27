<template>
  <b-overlay :show="loading">
    <div class="formBoder">
    <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
      <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
        <b-row>
          <b-col v-if="dependentList.length" lg="6" md="6" sm="12" xs="12">
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
          <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Is Primary" vid="is_primary" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="is_primary">
                <template v-slot:label>
                  Is Primary
                </template>
                <b-form-checkbox
                  id="is_primary"
                  v-model="form.is_primary"
                  :state="errors[0] ? false : (valid ? true : null)"
                  switch>
                  </b-form-checkbox>
                <div class="d-block invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="12" md="12" sm="12" xs="12">
          <ValidationProvider name="Organization Name" vid="organization_name" rules="required" v-slot="{ errors }">
            <b-form-group
              label-for="organization_name"
            >
            <template v-slot:label>
              Organization Name <span class="text-danger">*</span>
            </template>
              <b-form-input
                id="organization_name"
                v-model="form.organization_name"
                placeholder="Enter Organization Name"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Desingation" vid="designation" rules="required" v-slot="{ errors }">
            <b-form-group
              label-for="designation"
            >
            <template v-slot:label>
              Designation <span class="text-danger">*</span>
            </template>
              <b-form-input
                id="designation"
                v-model="form.designation"
                placeholder="Enter Designation"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Start Date" vid="start_date" rules="required" v-slot="{ errors }">
            <b-form-group
              label-for="start_date">
              <template v-slot:label>
               Start Date <span class="text-danger">*</span>
              </template>
              <flat-pickr
                id="start_date"
                v-model="form.start_date"
                class="form-control"
                placeholder="Select Start Date"
                :state="errors[0] ? false : (valid ? true : null)"
                :config="flatPickrConfig"
              />
              <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col v-if="!form.still_working" lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="End Date" vid="end_date" rules="" v-slot="{ errors }">
            <b-form-group
              label-for="end_date">
              <template v-slot:label>
               End Date <span class="text-danger"></span>
              </template>
              <flat-pickr
                id="end_date"
                v-model="form.end_date"
                class="form-control"
                placeholder="Select Start Date"
                :state="errors[0] ? false : (valid ? true : null)"
                :config="flatPickrConfig"
              />
              <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col v-if="form.start_date" lg="6" md="6" sm="8" xs="8">
          <ValidationProvider name="Still Working" vid="still_working" rules="required" v-slot="{ errors }">
            <b-form-group
              label-for="still_working">
              <template v-slot:label>
                Still Working
              </template>
              <b-form-checkbox
                id="still_working"
                v-model="form.still_working"
                :state="errors[0] ? false : (valid ? true : null)"
                switch>
                </b-form-checkbox>
              <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="8" xs="8">
          <ValidationProvider name="Is Foreign" vid="is_foreign" rules="required" v-slot="{ errors }">
            <b-form-group
              label-for="is_foreign">
              <template v-slot:label>
                Is Foreign
              </template>
              <b-form-checkbox
                id="is_foreign"
                v-model="form.is_foreign"
                :state="errors[0] ? false : (valid ? true : null)"
                switch>
                </b-form-checkbox>
              <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12" v-if="form.is_foreign === true">
          <ValidationProvider name="Country" vid="country_id" v-slot="{ errors }">
            <b-form-group
              label-for="country_id"
            >
            <template v-slot:label>
              Country
            </template>
            <b-input-group>

              <template #prepend>
                <treeselect
                id="country_id"
                :options="countryCodeList"
                placeholder="Select Country Code"
                v-model="form.country_id"
                :state="errors[0] ? false : (valid ? true : null)"
                />
              </template>
            </b-input-group>
              <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
          </b-col>
          <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Location" vid="location" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="location"
              >
              <template v-slot:label>
              Location <span class="text-danger">*</span>
              </template>
              <b-input-group>
                  <b-form-textarea
                    id="location"
                    v-model="form.location"
                    placeholder="Enter location"
                    :state="errors[0] ? false : (valid ? true : null)"
                    rows="2"
                    max-rows="6"
                  ></b-form-textarea>
              </b-input-group>
                <div class="d-block invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="12" md="12" sm="12" xs="12">
            <ValidationProvider name="Responsibility" vid="responsibility" v-slot="{ errors }">
              <b-form-group
                label-for="responsibility"
              >
              <template v-slot:label>
                Responsibility
              </template>
              <b-input-group>
                  <b-form-textarea
                    id="note"
                    v-model="form.responsibility"
                    placeholder="Enter responsibility"
                    :state="errors[0] ? false : (valid ? true : null)"
                    rows="2"
                    max-rows="6"
                  ></b-form-textarea>
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
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'

export default {
  props: ['leadDetails', 'editItem', 'dependentList', 'countryList'],
  components: {
    Treeselect,
    flatPickr
  },
  data () {
    return {
      SaveButton: this.editItem ? 'Update' : 'Save',
      form: {
        lead_id: '',
        dependent_id: null,
        organization_name: '',
        designation: '',
        start_date: '',
        end_date: '',
        still_working: false,
        location: '',
        is_foreign: false,
        is_primary: false,
        country_id: null,
        responsibility: ''
      },
      errors: [],
      valid: null,
      loading: false,
      flatPickrConfig: {
        // maxDate: 'today',
        dateFormat: 'd-m-Y'
      }
    }
  },
  created () {
    if (this.editItem) {
      this.form = JSON.parse(this.editItem)
      // this.form.is_foreign === 0 ? false : true
      // console.log('this.form', this.form)
    }
    this.form.lead_id = this.leadDetails.id
  },
  computed: {
    countryCodeList: function () {
      return this.countryList.map(item => {
        return {
          id: item.id,
          label: `${item.name}`
          // label: `${item.name}(+${item.calling_code})`
        }
      })
    },
    visaTypeList: function () {
      const statusGroupList = this.cn(this.$store.state, 'dropdowns.status_groups')
      return this.getStatuseByGroupCode(statusGroupList, 'visa_types')
    }
  },
  watch: {
  },
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_lead_employment_history_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_lead_employment_history_data', this.form)
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
