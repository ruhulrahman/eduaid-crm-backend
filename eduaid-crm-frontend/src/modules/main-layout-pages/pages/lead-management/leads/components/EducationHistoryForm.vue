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
            <ValidationProvider name="Institute Country" vid="institute_country_id" rules="required" v-slot="{ errors }">
                <b-form-group
                  label-for="institute_country_id"
                >
                <template v-slot:label>
                  Institute Country <span class="text-danger">*</span>
                </template>
                <treeselect
                  id="institute_country_id"
                  :options="instituteCountryList"
                  placeholder="Select Institute Country"
                  v-model="form.institute_country_id"
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
            <ValidationProvider name="Institute Name" vid="institute_name" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="institute_name"
              >
              <template v-slot:label>
                Institute Name <span class="text-danger">*</span>
              </template>
                <b-form-input
                  id="institute_name"
                  v-model="form.institute_name"
                  placeholder="Enter Institute Name"
                  :state="errors[0] ? false : (valid ? true : null)"
                ></b-form-input>
                <div class="invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
        <b-col lg="3" md="3" sm="8" xs="8">
          <ValidationProvider name="foreign Institute" vid="is_foreign_institute" rules="required" v-slot="{ errors }">
            <b-form-group
              label-for="is_foreign_institute">
              <template v-slot:label>
                Is Foreign institute
              </template>
              <b-form-checkbox
                id="is_foreign_institute"
                v-model="form.is_foreign_institute"
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
          <ValidationProvider name="Course Level" vid="course_level_id" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="course_level_id"
              >
              <template v-slot:label>
                Course Level<span class="text-danger">*</span>
              </template>
              <treeselect
                id="course_level_id"
                :options="courseLevelList"
                placeholder="Select Course Level"
                v-model="form.course_level_id"
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
          <ValidationProvider name="Course Name" vid="course_name" rules="required" v-slot="{ errors }">
            <b-form-group
              label-for="course_name"
            >
            <template v-slot:label>
              Course Name <span class="text-danger">*</span>
            </template>
              <b-form-input
                id="course_name"
                v-model="form.course_name"
                placeholder="Enter Course Name"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Result Type" vid="result_type_id" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="result_type_id"
              >
              <template v-slot:label>
                Result Type<span class="text-danger">*</span>
              </template>
              <treeselect
                id="result_type_id"
                placeholder="Select result Type"
                v-model="form.result_type_id"
                :options="resultTypeList"
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
          <ValidationProvider name="Marks" vid="marks" rules="required" v-slot="{ errors }">
            <b-form-group
              label-for="marks"
            >
            <template v-slot:label>
              Marks <span class="text-danger">*</span>
            </template>
              <b-form-input
                id="marks"
                v-model="form.marks"
                placeholder="Enter marks"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>

        <b-col v-if="showCgpaField" lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Grade" vid="grade" rules="" v-slot="{ errors }">
            <b-form-group
              label-for="grade"
            >
            <template v-slot:label>
              Grade <span class="text-danger">*</span>
            </template>
              <b-form-input
                id="marks"
                v-model="form.grade"
                placeholder="Enter grade"
                :state="errors[0] ? false : (valid ? true : null)"
                ></b-form-input>
                <!-- oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" -->
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>

        <b-col v-if="showCgpaField" lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="grade scale" vid="grade_scale" rules="" v-slot="{ errors }">
            <b-form-group
              label-for="grade_scale"
            >
            <template v-slot:label>
              Grade Scale<span class="text-danger">*</span>
            </template>
              <b-form-input
                id="grade_scale"
                v-model="form.grade_scale"
                placeholder="Enter grade scale"
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
          <ValidationProvider name="Year Of Passing" vid="year_of_passing" rules="required" v-slot="{ errors }">
            <b-form-group
              label-for="year_of_passing"
            >
            <template v-slot:label>
              Year Of Passing <span class="text-danger">*</span>
            </template>
            <b-input-group>

              <!-- <template #prepend> -->
              <b-form-select
                plain
                id="year_of_passing"
                v-model="form.year_of_passing"
                :options="yearPassingList"
                placeholder="Select year passing"
                :state="errors[0] ? false : (valid ? true : null)"
                >
                <template v-slot:first>
                    <b-form-select-option :value=null>Select</b-form-select-option>
                  </template>
              </b-form-select>
              <!-- </template> -->
            </b-input-group>
              <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
          </b-col>
          <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Duration" vid="duration" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="duration"
              >
              <template v-slot:label>
              Duration <span class="text-danger">*</span>
              </template>
              <b-input-group>
                  <b-form-input
                    id="duration"
                    v-model="form.duration"
                    placeholder="Enter duration"
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
        institute_country_id: null,
        institute_name: '',
        is_foreign_institute: false,
        course_level_id: null,
        course_name: '',
        result_type_id: null,
        marks: '',
        grade: '',
        grade_scale: '',
        year_of_passing: null,
        duration: ''
      },
      errors: [],
      valid: null,
      loading: false,
      showCgpaField: false,
      flatPickrConfig: {
        // maxDate: 'today',
        dateFormat: 'd-m-Y'
      }
    }
  },
  computed: {
    instituteCountryList: function () {
      return this.countryList.map(item => {
        return {
          id: item.id,
          label: `${item.name}`
          // label: `${item.name}(+${item.calling_code})`
        }
      })
    },
    resultTypeList: function () {
      const statusGroupList = this.cn(this.$store.state, 'dropdowns.status_groups')
      return this.getStatuseByGroupCode(statusGroupList, 'result_types')
    },
    courseLevelList: function  () {
      return this.$store.state.dropdowns.courseLevels.filter(item => item.active === 1)
    },
    yearPassingList: function () {
      return this.$store.state.dropdowns.yearList.map(el => {
        return Object.assign({ value: el, text: el})
      })
    }
  },
  watch: {
    'form.result_type_id': function (newValue) {
      const resultTypeObj = this.resultTypeList.find(item => item.value === newValue)
      if (resultTypeObj && resultTypeObj.code === 'cgpa') {
        this.showCgpaField = true
      } else {
        this.showCgpaField = false
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
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_education_history_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_education_history_data', this.form)
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
