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
          <ValidationProvider name="Name" vid="name" rules="required" v-slot="{ errors }">
            <b-form-group
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
        <b-col lg="12" md="12" sm="12" xs="12">
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
                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                ></b-form-input>
            </b-input-group>
              <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
          </b-col>
          <b-col lg="6" md="6" sm="12" xs="12">
              <ValidationProvider name="Address" vid="address" rules="required" v-slot="{ errors }">
                <b-form-group
                  label-for="address"
                >
                <template v-slot:label>
                  Address <span class="text-danger">*</span>
                </template>
                <b-input-group>
                    <b-form-input
                    id="address"
                    v-model="form.address"
                    placeholder="Enter Address"
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
              <ValidationProvider name="Email" vid="email" v-slot="{ errors }">
                <b-form-group
                  label-for="email"
                >
                <template v-slot:label>
                Email
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
          <b-col lg="12" md="12" sm="12" xs="12">
            <ValidationProvider name="Relation" vid="relation" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="relation"
              >
              <template v-slot:label>
              Relation <span class="text-danger">*</span>
              </template>
              <b-input-group>
                  <b-form-textarea
                    id="relation"
                    v-model="form.relation"
                    placeholder="Enter Relation"
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
            <ValidationProvider name="Note" vid="note" v-slot="{ errors }">
              <b-form-group
                label-for="note"
              >
              <template v-slot:label>
              Note
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
  props: ['leadDetails', 'editItem', 'dependentList', 'countryList'],
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
        name: '',
        mobile: '',
        address: '',
        mobile_country_id: null,
        email: '',
        relation: '',
        note: ''
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
    mobileCountryCodeList: function () {
      return this.countryList.map(item => {
        return {
          id: item.id,
          label: `${item.name}(+${item.calling_code})`
        }
      })
    }
  },
  watch: {
  },
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_lead_emergency_contact_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_lead_emergency_contact_data', this.form)
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
