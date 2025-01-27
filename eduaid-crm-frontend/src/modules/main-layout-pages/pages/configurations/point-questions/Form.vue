<template>
  <b-overlay :show="loading">
    <div class="formBoder">
    <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
      <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
        <b-row>
        <!-- <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Country" vid="country_id" rules="required" v-slot="{ errors }">
            <b-form-group
                class="row"
                label-cols-sm="12"
                label-for="country_id"
              >
              <template v-slot:label>
                Country <span class="text-danger">*</span>
              </template>
              <treeselect
                id="country_id"
                :options="serviceWishCountryList"
                placeholder="Select Country"
                v-model="form.country_id"
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
          <ValidationProvider name="Service" vid="service_id" rules="required" v-slot="{ errors }">
            <b-form-group
                class="row"
                label-cols-sm="12"
                label-for="service_id"
              >
              <template v-slot:label>
                Service <span class="text-danger">*</span>
              </template>
              <treeselect
                id="service_id"
                :options="serviceListFiltered"
                placeholder="Select Service"
                v-model="form.service_id"
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
        <b-col lg="12" md="12" sm="12" xs="12">
          <ValidationProvider name="Question" vid="question" rules="required" v-slot="{ errors }">
            <b-form-group
              class="row"
              label-cols-sm="12"
              label-for="question"
            >
            <template v-slot:label>
              Question <span class="text-danger">*</span>
            </template>
              <b-form-input
                id="question"
                v-model="form.question"
                placeholder="Enter question"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12">
          <ValidationProvider name="Total weight" vid="total_weight" rules="required|numeric" v-slot="{ errors }">
            <b-form-group
              class="row"
              label-cols-sm="12"
              label-for="total_weight"
            >
            <template v-slot:label>
              Total weight <span class="text-danger">*</span>
            </template>
              <b-form-input
                id="total_weight"
                v-model="form.total_weight"
                placeholder="Enter Total weight"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="12" xs="12" class="pb-0 mb-0">
          <ValidationProvider name="Pass mark" vid="pass_mark" rules="required|numeric" v-slot="{ errors }">
            <b-form-group
              class="row"
              label-cols-sm="12"
              label-for="pass_mark"
            >
            <template v-slot:label>
              Pass mark <span class="text-danger">*</span>
            </template>
              <b-form-input
                id="pass_mark"
                v-model="form.pass_mark"
                placeholder="Enter Pass mark"
                :state="errors[0] ? false : (valid ? true : null)"
            ></b-form-input>
              <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
       </b-row>
       <hr/>
       <b-row>
        <b-col>
          <h6>Question Point Breakdowns</h6>
        </b-col>
       </b-row>
       <b-row v-for="(item, index) in form.breakdowns" :key="index">
        <b-col>
          <b-row>
            <b-col lg="6" md="6" sm="12" xs="12">
              <ValidationProvider name="Question Value" vid="value" rules="required" v-slot="{ errors }">
                <b-form-group
                  class="row"
                  label-cols-sm="12"
                  label-for="value"
                >
                <template v-slot:label>
                  Question Value <span class="text-danger">*</span>
                </template>
                <b-input-group>
                  <b-form-input
                    :id="'value-'+index"
                    v-model="item.value"
                    placeholder="Enter Question Value"
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
              <ValidationProvider name="Question Point" vid="point" rules="required|numeric" v-slot="{ errors }">
                <b-form-group
                  class="row"
                  label-cols-sm="12"
                  label-for="point"
                >
                <template v-slot:label>
                  Question point <span class="text-danger">*</span>
                </template>
                <b-input-group>
                  <b-form-input
                    :id="'point-'+index"
                    v-model="item.point"
                    placeholder="Enter Question point"
                    :state="errors[0] ? false : (valid ? true : null)"
                  ></b-form-input>
                </b-input-group>
                  <div class="d-block invalid-feedback">
                    {{ errors[0] }}
                  </div>
                </b-form-group>
              </ValidationProvider>
            </b-col>
          </b-row>
        </b-col>
        <b-col lg="1" md="1" sm="1" xs="1" class="d-flex align-items-center pl-0 ml-0">
          <b-button @click="addNewBreakdown()" variant="success" size="sm" class="mr-1"><i class="ri-add-fill"></i></b-button>
          <b-button v-if="form.breakdowns.length > 1" @click="removeBreakdown(index)" variant="danger" size="sm" class=""><i class="ri-subtract-fill"></i></b-button>
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
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
  props: ['editItem', 'serviceList', 'countryList'],
  components: {
    Treeselect
  },
  data () {
    return {
      SaveButton: this.editItem ? 'Update' : 'Save',
      form: {
        question: '',
        service_id: null,
        country_id: null,
        total_weight: 0,
        pass_mark: 0,
        active: 1,
        breakdowns: [
          {
            value: '',
            point: 0
          }
        ]
      },
      errors: [],
      valid: null,
      loading: false
    }
  },
  computed: {
    serviceWishCountryList: function () {
      let fileterCountryList = []
      if (this.serviceList.length) {
        this.serviceList.forEach(item => {
          const countryObj = this.countryList.find(country => country.id === item.country_id)
          fileterCountryList.push(countryObj)
        })
      } else {
        fileterCountryList = this.countryList
      }
      return fileterCountryList
    },
    serviceListFiltered: function () {
      if (this.form.country_id) {
        return this.serviceList.filter(item => item.country_id === this.form.country_id)
      } else {
        // return this.serviceList
        return []
      }
    }
  },
  created () {
    if (this.editItem) {
      this.form = JSON.parse(this.editItem)
    }
  },
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_point_question_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_point_question_data', this.form)
      }
      this.loading = false
      if (result.success) {
        this.$emit('reloadList', true)
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.$bvModal.hide('modal-1')
        // this.getCommonDropdownList()
      } else {
        this.$refs.form.setErrors(result.errors)
      }
    },
    addNewBreakdown: function () {
      this.form.breakdowns.push({
        value: '',
        point: 0
      })
    },
    removeBreakdown: function (index) {
      this.form.breakdowns.splice(index, 1)
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
