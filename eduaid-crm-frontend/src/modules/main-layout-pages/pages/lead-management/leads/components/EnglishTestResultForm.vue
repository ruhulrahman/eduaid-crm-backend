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
                  v-model="form.dependent_id"
                  :options="dependentList"
                  placeholder="Select Dependent"
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
            <ValidationProvider name="Lead English Test" vid="lead_english_test_id" rules="required">
              <b-form-group
                label-for="lead_english_test_id"
                slot-scope="{ valid, errors }"
              >
              <template v-slot:label>
                Lead English Test
              </template>
              <treeselect
                id="lead_english_test_id"
                v-model="form.lead_english_test_id"
                :options="leadEnglishTestList"
                placeholder="Select Dependent"
                :state="errors[0] ? false : (valid ? true : null)"
                />
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Child Language" vid="child_language_test_id" rules="required" v-slot="{ errors }">
                <b-form-group
                  label-for="child_language_test_id"
                >
                <template v-slot:label>
                  Child Language Test <span class="text-danger">*</span>
                </template>
                <treeselect
                  id="child_language_test_id"
                  v-model="form.child_language_test_id"
                  :options="childLanguageTestList"
                  placeholder="Select Child Language"
                  :state="errors[0] ? false : (valid ? true : null)"
                  />
              <div class="d-block invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Result" vid="result" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="result"
              >
              <template v-slot:label>
                Result<span class="text-danger">*</span>
              </template>
                <b-form-input
                  id="result"
                  v-model="form.result"
                  placeholder="Enter result"
                  :state="errors[0] ? false : (valid ? true : null)"
                  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                ></b-form-input>
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
// import the component
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'

export default {
  props: ['leadDetails', 'editItem', 'dependentList'],
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
        lead_english_test_id: null,
        child_language_test_id: null,
        result: ''
      },
      errors: [],
      valid: null,
      loading: false,
      flatPickrConfig: {
        // maxDate: 'today',
        dateFormat: 'd-m-Y'
      },
      leadEnglishTestList: [],
      childLanguageTestList: []
    }
  },
  created () {
    if (this.editItem) {
      this.form = JSON.parse(this.editItem)
    }
    this.form.lead_id = this.leadDetails.id
  },
  mounted () {
    this.getLeadEnglishTest(this.form.lead_id)
  },
  computed: {
    levelList: function  () {
      return this.$store.state.dropdowns.courseLevels.filter(item => item.active === 1)
    }
  },
  watch: {
    // 'form.dependent_id': function (dependentId) {

    // },
    'form.lead_english_test_id': function (parentId) {
      this.getLanguageTestDropdowns(parentId)
    }
  },
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_lead_english_test_result_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_lead_english_test_result_data', this.form)
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
    async getLeadEnglishTest (lead_id) {
      // var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_lead_english_test_dropdown_list', { dependent_id: dependentId})
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_lead_english_test_dropdown_list', { lead_id: lead_id })
      if (result.success) {
        this.leadEnglishTestList = result.data
      }
    },
    async getLanguageTestDropdowns (parentId) {
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_child_english_test_dropdown_list', { parent_id: parentId})
      if (result.success) {
        this.childLanguageTestList = result.data
      }
    },
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
