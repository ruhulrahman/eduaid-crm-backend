<template>
    <b-overlay :show="loading">
      <div class="formBoder">
      <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
        <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
          <b-row>
            <b-col lg="6" md="6" sm="12" xs="12">
              <ValidationProvider name="Stage No" vid="stage_no" rules="required" v-slot="{ errors }">
                <b-form-group
                  label-for="stage_no"
                >
                <template v-slot:label>
                  Stage No<span class="text-danger">*</span>
                </template>
                  <b-form-input
                    id="stage_no"
                    v-model="form.stage_no"
                    placeholder="Enter Stage No"
                    :state="errors[0] ? false : (valid ? true : null)"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                  ></b-form-input>
                  <div class="invalid-feedback">
                    {{ errors[0] }}
                  </div>
                </b-form-group>
              </ValidationProvider>
            </b-col>
            <b-col lg="6" md="6" sm="12" xs="12">
              <ValidationProvider name="SL No" vid="sl_no" rules="required" v-slot="{ errors }">
                <b-form-group
                  label-for="sl_no"
                >
                <template v-slot:label>
                    SL No<span class="text-danger">*</span>
                </template>
                  <b-form-input
                    id="sl_no"
                    v-model="form.sl_no"
                    placeholder="Enter Serial no"
                    :state="errors[0] ? false : (valid ? true : null)"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                  ></b-form-input>
                  <div class="invalid-feedback">
                    {{ errors[0] }}
                  </div>
                </b-form-group>
              </ValidationProvider>
            </b-col>
            <b-col lg="6" md="6" sm="12" xs="12">
              <ValidationProvider name="Amount" vid="amount" rules="required" v-slot="{ errors }">
                <b-form-group
                  label-for="amount"
                >
                <template v-slot:label>
                    Amount<span class="text-danger">*</span>
                </template>
                  <b-form-input
                    id="amount"
                    v-model="form.amount"
                    placeholder="Enter Amount"
                    :state="errors[0] ? false : (valid ? true : null)"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                  ></b-form-input>
                  <div class="invalid-feedback">
                    {{ errors[0] }}
                  </div>
                </b-form-group>
              </ValidationProvider>
            </b-col>
            <b-col lg="6" md="6" sm="12" xs="12">
              <ValidationProvider name="Remarks" vid="remarks" v-slot="{ errors }">
                <b-form-group
                  label-for="remarks"
                >
                <template v-slot:label>
                    Remarks
                </template>
                  <b-form-textarea
                    id="remarks"
                    v-model="form.remarks"
                    placeholder="Enter Remarks"
                    row="3"
                    :state="errors[0] ? false : (valid ? true : null)"
                  ></b-form-textarea>
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
      <!-- <pre>{{ languageTestList }}</pre> -->
      </div>
    </b-overlay>
  </template>
  <script>
  import RestApi, { baseURL } from '@/config'

  export default {
    props: ['leadDetails', 'editItem', 'dependentList'],
    components: {
    },
    data () {
      return {
        SaveButton: this.editItem ? 'Update' : 'Save',
        form: {
          lead_id: '',
          stage_no: '',
          sl_no: '',
          amount: '',
          remarks: ''
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
      this.getLeadPaymentLastSerial()
    },
    methods: {
      async submitData () {
        this.loading = true
        let result = ''
        if (this.form.id) {
          result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_lead_payment_data', this.form)
        } else {
          result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_lead_payment_data', this.form)
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
      },
      async getLeadPaymentLastSerial () {
        const result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_lead_payment_last_serial')
        if (result.success) {
          this.form.sl_no = result.data
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
