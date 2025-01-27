<template>
  <b-overlay :show="loading">
    <div class="formBoder">
    <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
      <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
        <b-row>
          <b-col lg="12" md="12" sm="12" xs="12">
            <ValidationProvider name="Vat Percent" vid="vat_percent" rules="required" v-slot="{ errors }">
              <b-form-group
                id="vat_percent"
                label="Vat Percent"
                label-for="vat_percent"
              >
              <template v-slot:label>
                VAT Percent
              </template>
              <b-input-group append="%">
                <b-form-input
                  id="vat_percent"
                  v-model="form.vat_percent"
                  placeholder="Enter VAT Percent"
                  :state="errors[0] ? false : (valid ? true : null)"
                  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                ></b-form-input>
              </b-input-group>
                <div class="d-block invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="12" md="12" sm="12" xs="12">
            <ValidationProvider name="start Date" vid="start_date" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="start_date">
                <template v-slot:label>
                  Start Date
                </template>
                <flat-pickr
                  id="start_date"
                  v-model="form.start_date"
                  class="form-control"
                  placeholder="select Date"
                  :state="errors[0] ? false : (valid ? true : null)"
                  :config="flatPickrConfig"
                />
                <div class="d-block invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="12" md="12" sm="12" xs="12">
            <ValidationProvider name="End Date" vid="end_date" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="end_date">
                <template v-slot:label>
                  End Date
                </template>
                <flat-pickr
                  id="end_date"
                  v-model="form.end_date"
                  class="form-control"
                  placeholder="select Date"
                  :state="errors[0] ? false : (valid ? true : null)"
                  :config="EndflatPickrConfig"
                />
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
import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'
import RestApi, { baseURL } from '@/config'
import moment from 'moment'

export default {
  props: ['editItem'],
  components: {
    flatPickr
  },
  data () {
    return {
      SaveButton: this.editItem ? 'Update' : 'Save',
      form: {
        vat_percent: '',
        start_date: '',
        end_date: ''
      },
      errors: [],
      valid: null,
      loading: false,
      customerList: [],
      subscriptionPlanList: [],
      paymentMediaList: [],
      flatPickrConfig: {
        // maxDate: 'today',
        dateFormat: 'd-m-Y'
      },
      EndflatPickrConfig: {
        dateFormat: 'd-m-Y'
      }
    }
  },
  created () {
    if (this.editItem) {
      this.form = this.clone_object(this.editItem)
      this.form.start_date = moment(this.form.start_date).format('DD-MM-YYYY')
      this.form.end_date = moment(this.form.end_date).format('DD-MM-YYYY')
    }
  },
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_vat_percent_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_vat_percent_data', this.form)
      }
      this.loading = false
      if (result.success) {
        this.$emit('loadList', true)
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
