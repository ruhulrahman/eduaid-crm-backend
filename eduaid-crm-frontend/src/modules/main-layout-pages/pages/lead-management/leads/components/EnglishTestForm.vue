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
              <div class="d-block invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Language Test" vid="language_test_id" rules="required" v-slot="{ errors }">
              <b-form-group label-for="language_test_id">
                <template v-slot:label>
                  Language Test <span class="text-danger">*</span>
                </template>
                <treeselect
                  id="language_test_id"
                  v-model="form.language_test_id"
                  :options="languageTestList"
                  placeholder="Select Language Test"
                  :state="errors[0] ? false : (valid ? true : null)"
                  />
                <div class="d-block invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <!-- <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Language Test" vid="language_test_id" rules="required" v-slot="{ errors }">
                <b-form-group
                  label-for="language_test_id"
                >
                <template v-slot:label>
                  Language Test <span class="text-danger">*</span>
                </template>
                <treeselect
                  id="language_test_id"
                  :options="languageTestList"
                  placeholder="Select Language Test"
                  v-model="form.language_test_id"
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
          </b-col> -->
          <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Level" vid="level_id" rules="required" v-slot="{ errors }">
                <b-form-group
                  label-for="level_id"
                >
                <template v-slot:label>
                  Level <span class="text-danger">*</span>
                </template>
                <treeselect
                  id="level_id"
                  :options="levelList"
                  placeholder="Select Level"
                  v-model="form.level_id"
                  :state="errors[0] ? false : (valid ? true : null)"
                  />
                <div class="d-block invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="3" md="3" sm="8" xs="8">
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
          <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Test Date" vid="test_date" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="test_date">
                <template v-slot:label>
                 Test Date <span class="text-danger">*</span>
                </template>
                <flat-pickr
                  id="test_date"
                  v-model="form.test_date"
                  class="form-control"
                  placeholder="Select Test Date"
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
            <ValidationProvider name="Expire Date" vid="expire_date" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="expire_date">
                <template v-slot:label>
                 Expire Date <span class="text-danger">*</span>
                </template>
                <flat-pickr
                  id="expire_date"
                  v-model="form.expire_date"
                  class="form-control"
                  placeholder="Select Expire Date"
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
            <ValidationProvider name="Over All Result" vid="over_all_result" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="over_all_result"
              >
              <template v-slot:label>
                Over All Result<span class="text-danger">*</span>
              </template>
                <b-form-input
                  id="over_all_result"
                  v-model="form.over_all_result"
                  placeholder="Enter Over all result"
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
import moment from 'moment'

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
        language_test_id: null,
        level_id: null,
        is_primary: false,
        test_date: '',
        expire_date: '',
        over_all_result: ''
      },
      errors: [],
      valid: null,
      loading: false,
      flatPickrConfig: {
        // maxDate: 'today',
        dateFormat: 'd-m-Y'
      },
      languageTestList: []
    }
  },
  created () {
    if (this.editItem) {
      this.form = JSON.parse(this.editItem)
      this.form.test_date = moment(this.form.test_date).format('DD-MM-YYYY')
      this.form.expire_date = moment(this.form.expire_date).format('DD-MM-YYYY')
    }
    this.form.lead_id = this.leadDetails.id
  },
  mounted () {
    this.getLanguageTestDropdowns()
  },
  computed: {
    levelList: function  () {
      return this.$store.state.dropdowns.courseLevels.filter(item => item.active === 1)
    }
  },
  watch: {
  },
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_lead_english_test_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_lead_english_test_data', this.form)
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
    async getLanguageTestDropdowns () {
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_language_test_dropdown_list')
      if (result.success) {
        this.languageTestList = result.data
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
