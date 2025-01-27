<template>
  <b-overlay :show="loading">
    <div class="formBoder">
    <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
      <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
          <!-- <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Service" vid="service_id" rules="required" v-slot="{ errors }">
                <b-form-group
                  label-for="service_id"
                >
                <template v-slot:label>
                  Service <span class="text-danger">*</span>
                </template>
                <treeselect
                  id="service_id"
                  v-model="form.service_id"
                  :options="serviceList"
                  placeholder="Select service"
                  :state="errors[0] ? false : (valid ? true : null)"
                  />
              <div class="d-block invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col> -->
          <!-- <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Question" vid="question_id" rules="required" v-slot="{ errors }">
                <b-form-group
                  label-for="question_id"
                >
                <template v-slot:label>
                  Question <span class="text-danger">*</span>
                </template>
                <treeselect
                  id="question_id"
                  v-model="form.question_id"
                  :options="questionListData"
                  placeholder="Select question"
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

        <b-row v-for="(item, index) in form.questions " :key="index">
          <b-col lg="6" md="6" sm="12" xs="12">
            {{ item.question }}
          </b-col>
          <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Answer" :vid="`qp_breakdown_id${index}`" rules="required" :custom-messages="{ required: 'Please answer this question'}" v-slot="{ errors }">
              <b-form-group label-for="qp_breakdown_id">
              <!-- <template v-slot:label>
                {{ item.question }} <span class="text-danger">*</span>
              </template> -->
              <treeselect
                  :id="'question-'+index"
                  v-model="item.qp_breakdown_id"
                  :options="item.breakdowns"
                  placeholder="Select Answer"
                  :state="errors[0] ? false : (valid ? true : null)"
                  />
                <div class="d-block invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>



          <!-- <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Question" vid="question_id" rules="required">
              <b-form-group
                label-for="question_id"
                slot-scope="{ valid, errors }"
              >
              <template v-slot:label>
                Question
              </template>
              <treeselect
                  id="service_id"
                  v-model="form.question_id"
                  :options="questionList"
                  placeholder="Select Question"
                  :state="errors[0] ? false : (valid ? true : null)"
                  />
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Question Point BreakDowns" vid="qp_breakdown_id" rules="required">
              <b-form-group
                label-for="qp_breakdown_id"
                slot-scope="{ valid, errors }"
              >
              <template v-slot:label>
                Question Point Breakdowns
              </template>
              <treeselect
                  id="service_id"
                  v-model="form.qp_breakdown_id"
                  :options="breakdownList"
                  placeholder="Select Question Point Breakdown"
                  :state="errors[0] ? false : (valid ? true : null)"
                  />
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
              </b-form-group>
            </ValidationProvider>
          </b-col>
          <b-col lg="6" md="6" sm="12" xs="12">
            <ValidationProvider name="Point" vid="point" rules="required" v-slot="{ errors }">
              <b-form-group
                label-for="point"
              >
              <template v-slot:label>
                Point<span class="text-danger">*</span>
              </template>
                <b-form-input
                  id="point"
                  v-model="form.point"
                  placeholder="Enter Point"
                  :state="errors[0] ? false : (valid ? true : null)"
                  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                ></b-form-input>
                <div class="invalid-feedback">
                  {{ errors[0] }}
                </div>
              </b-form-group>
            </ValidationProvider>
          </b-col> -->
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
      <!-- <pre>{{ form }} </pre> -->
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
  props: ['leadDetails', 'editItem'],
  components: {
    Treeselect,
    flatPickr
  },
  data () {
    return {
      SaveButton: this.editItem ? 'Update' : 'Save',
      form: {
        lead_id: '',
        lead_service_id: '',
        service_id: null,
        question_id: null,
        qp_breakdown_id: null,
        questions: [],
        point: ''
      },
      errors: [],
      valid: null,
      loading: false,
      questionListData: [],
      questionPointBreakDown: [],
      questionList: []
    }
  },
  created () {
    if (this.editItem) {
      var tmp = JSON.parse(this.editItem)
      this.form.lead_id = tmp.lead_id
      this.form.service_id = tmp.service_id
      this.form.lead_service_id = tmp.id
      this.form.questions = tmp.questions
      // this.questionList = tmp.questions
    }
    // this.form.lead_id = this.leadDetails.id
  },
  mounted () {
    // this.getQuestionListServicewise()
    // this.getQuestionPointBreakdown()
  },
  computed: {
    // resultTypeList: function () {
    //   const statusGroupList = this.cn(this.$store.state, 'dropdowns.status_groups')
    //   return this.getStatuseByGroupCode(statusGroupList, 'result_types')
    // },
    // serviceList: function  () {
    //   return this.$store.state.dropdowns.services.filter(item => item.active === 1)
    // },
    // questionList: function  () {
    //   return this.questionListData.filter(item => item.service_id === this.form.service_id)
    // },
    // breakdownList: function () {
    //   return this.questionPointBreakDown.filter(item => item.question_id === this.form.question_id)
    // }
  },
  watch: {
    // 'form.question_id': function (newVal, oldVal) {
    //   if (newVal !== oldVal) {
    //     this.breakdownList = this.getQuestionWiseBreakdown(newVal)
    //   } else {
    //     this.breakdownList = []
    //   }
    // }
  },
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.questions.length) {
        const questionAnwsExist = this.form.questions.every(item => item.qp_breakdown_id > 0)
        if (!questionAnwsExist) {
          this.loading = false
          this.$toast.error({
            title: 'Validation Failed',
            message: 'Please answer all the questions'
          })
          return;
        }
      }

      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_question_point_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_question_point_data_v2', this.form)
      }
      this.loading = false
      if (result.success) {
        this.$emit('reloadList', true)
        this.$emit('reloadLeadDetails', this.form.lead_id)
        this.$emit('reloadLeadServicesById', this.form.lead_id)
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.$bvModal.hide('modal-1')
      } else {
        this.$refs.form.setErrors(result.errors)
      }
    },
    async getQuestionListServicewise () {
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_question_point')
      if (result.success) {
        this.questionListData = result.data
      }
    },
    async getQuestionPointBreakdown () {
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_question_point_breakdowns')
      if (result.success) {
        this.questionPointBreakDown = result.data
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
