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
            <ValidationProvider name="Contact Time" vid="contact_time" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="contact_time"
              >
              <template v-slot:label>
              Contact Time <span class="text-danger">*</span>
              </template>
              <b-input-group>
                  <b-form-input
                  id="contact_time"
                  v-model="form.contact_time"
                  placeholder="Enter Contact Time"
                  :state="errors[0] ? false : (valid ? true : null)"
                  ></b-form-input>
              </b-input-group>
                <div class="d-block invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="12" md="12" sm="12" xs="12">
            <ValidationProvider name="Contact Preference" vid="contact_preference" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="contact_preference"
              >
              <template v-slot:label>
              Contact Preference <span class="text-danger">*</span>
              </template>
              <b-input-group>
                  <b-form-textarea
                    id="contact_preference"
                    v-model="form.contact_preference"
                    placeholder="Enter Contact Preference"
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
            <ValidationProvider name="Note" vid="note" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="note"
              >
              <template v-slot:label>
              Note <span class="text-danger">*</span>
              </template>
              <b-input-group>
                  <b-form-textarea
                    id="note"
                    v-model="form.note"
                    placeholder="Enter Note"
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

export default {
  props: ['leadDetails', 'editItem', 'dependentList'],
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
        contact_time: '',
        contact_preference: '',
        note: ''
      },
      errors: [],
      valid: null,
      loading: false
    }
  },
  computed: {
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
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_lead_contact_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_lead_contact_data', this.form)
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
