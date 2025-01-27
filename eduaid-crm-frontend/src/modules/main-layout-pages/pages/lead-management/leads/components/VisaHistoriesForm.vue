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
              <ValidationProvider name="Visa Type" vid="visa_type_id" rules="required" v-slot="{ errors }">
                  <b-form-group
                    label-for="visa_type_id"
                  >
                  <template v-slot:label>
                    Visa Type <span class="text-danger">*</span>
                  </template>
                  <treeselect
                    id="visa_type_id"
                    :options="visaTypeList"
                    placeholder="Select Visa Type"
                    v-model="form.visa_type_id"
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
          <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="End Date" vid="end_date" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="end_date">
                <template v-slot:label>
                 End Date <span class="text-danger">*</span>
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
          <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Rejection Date" vid="rejection_date" v-slot="{ errors }">
              <b-form-group
                label-for="rejection_date">
                <template v-slot:label>
                 Rejection Date
                </template>
                <flat-pickr
                  id="rejection_date"
                  v-model="form.rejection_date"
                  class="form-control"
                  placeholder="Select Rejection Date"
                  :state="errors[0] ? false : (valid ? true : null)"
                  :config="flatPickrConfig"
                />
                <div class="d-block invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="6" md="6" sm="12" xs="12">
              <ValidationProvider name="Rejection Reason" vid="rejection_reason" v-slot="{ errors }">
                <b-form-group
                  label-for="rejection_reason"
                >
                <template v-slot:label>
                  Rejection Reason
                </template>
                <b-input-group>
                    <b-form-textarea
                      id="rejection_reason"
                      v-model="form.rejection_reason"
                      placeholder="Enter Rejection Reason"
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
           <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Country" vid="country_id" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="country_id"
              >
              <template v-slot:label>
                Country <span class="text-danger">*</span>
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
              <ValidationProvider name="Purpose" vid="purpose" v-slot="{ errors }">
                <b-form-group
                  label-for="purpose"
                >
                <template v-slot:label>
                  Purpose
                </template>
                <b-input-group>
                    <b-form-textarea
                      v-model="form.purpose"
                      placeholder="Enter purpose"
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
            <b-col lg="6" md="6" sm="12" xs="12">
              <ValidationProvider name="File" vid="file" v-slot="{ errors }">
                <b-form-group
                  label-for="file"
                >
                <template v-slot:label>
                  File <span style="font-size:10px" class="text-primary">(Maximum Size (2MB) & Accepted Format (pdf, csv, doc))</span>
                </template>
                <b-form-file
                    v-model="form.file"
                    @on:change="onFileChange"
                    placeholder="Choose a file or drop it here..."
                    drop-placeholder="Drop file here..."
                    ></b-form-file>
                  <div class="d-block invalid-feedback">
                    {{ fileValidationMsz }}
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
          visa_type_id: null,
          start_date: '',
          end_date: '',
          rejection_date: '',
          rejection_reason: '',
          country_id: null,
          purpose: '',
          file: null
        },
        fileValidationMsz: '',
        upload_file: [],
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
        // onChange (e) {
        //     this.fileValidationMsz = ''
        //     const input = event.target
        //     const file = input.files[0]
        //     if (file.size > 1024 * 2048) {
        //         e.preventDefault()
        //         this.fileValidationMsz = 'Maximum file size must be 2MB'
        //     }
        //     if (input.files && input.files[0]) {
        //         const reader = new FileReader()
        //         reader.onload = (e) => {
        //         this.form.file = e.target.result
        //         }
        //         reader.readAsDataURL(input.files[0])
        //     } else {
        //         this.form.file = ''
        //     }
        // },
        onFileChange (event) {
            this.upload_file = event.target.files[0]
            var reader = new FileReader()
            reader.onload = function () {
            var dataURL = reader.result
            this.currentUploadedPhoto = dataURL
        }
            reader.readAsDataURL(this.upload_file)
        },
      async submitData () {
        this.loading = true
        let result = ''
        if (this.form.id) {
          result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_lead_visa_histories_data', this.form)
        } else {
          result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_lead_visa_histories_data', this.form)
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
