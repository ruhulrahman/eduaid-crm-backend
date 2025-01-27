<template>
  <div class="section-wrapper">
    <b-breadcrumb>
      <b-breadcrumb-item to="/dashboard">
        <b-icon icon="house-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
        Dashboard
      </b-breadcrumb-item>
      <b-breadcrumb-item>Lead Management</b-breadcrumb-item>
      <b-breadcrumb-item to="/leads">Leads</b-breadcrumb-item>
      <b-breadcrumb-item active>{{ form.id ? 'Update Lead' : 'Add New Lead' }}</b-breadcrumb-item>
    </b-breadcrumb>
    <b-overlay :show="loading">
      <div class="form-wrapper mb-4">
        <b-card :title="form.id ? 'Update Lead' : 'Add New Lead'">
          <b-card-text>
          <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
            <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
              <b-row>
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="First Name" vid="first_name" rules="required|alpha_spaces" v-slot="{ errors }">
                    <b-form-group
                      label-for="first_name"
                    >
                    <template v-slot:label>
                      First Name <span class="text-danger">*</span>
                    </template>
                      <b-form-input
                        id="first_name"
                        v-model="form.first_name"
                        placeholder="Enter First Name"
                        :state="errors[0] ? false : (valid ? true : null)"
                      ></b-form-input>
                      <div class="invalid-feedback">
                        {{ errors[0] }}
                      </div>
                    </b-form-group>
                  </ValidationProvider>
                </b-col>
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="Last Name" vid="last_name" rules="required|alpha_spaces" v-slot="{ errors }">
                    <b-form-group
                      label-for="last_name"
                    >
                    <template v-slot:label>
                      Last Name <span class="text-danger">*</span>
                    </template>
                    <b-input-group>
                      <b-form-input
                        id="last_name"
                        v-model="form.last_name"
                        placeholder="Enter Last Name"
                        :state="errors[0] ? false : (valid ? true : null)"
                      ></b-form-input>
                    </b-input-group>
                      <!-- <span>Month</span> -->
                      <div class="d-block invalid-feedback">
                        {{ errors[0] }}
                      </div>
                    </b-form-group>
                  </ValidationProvider>
                </b-col>
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="Nick Name" vid="nick_name" rules="required|alpha_spaces" v-slot="{ errors }">
                    <b-form-group
                      label-for="nick_name"
                    >
                    <template v-slot:label>
                      Nick Name <span class="text-danger">*</span>
                    </template>
                    <b-input-group>
                      <b-form-input
                        id="nick_name"
                        v-model="form.nick_name"
                        placeholder="Enter Nick Name"
                        :state="errors[0] ? false : (valid ? true : null)"
                      ></b-form-input>
                    </b-input-group>
                      <!-- <span>Month</span> -->
                      <div class="d-block invalid-feedback">
                        {{ errors[0] }}
                      </div>
                    </b-form-group>
                  </ValidationProvider>
                </b-col>
                <b-col lg="12" md="12" sm="12" xs="12">
                  <ValidationProvider name="Description" vid="description" rules="required" v-slot="{ errors }">
                    <b-form-group
                      label-for="description"
                    >
                    <template v-slot:label>
                      Description <span class="text-danger">*</span>
                    </template>
                    <b-input-group>
                      <b-form-textarea
                        id="description"
                        v-model="form.description"
                        placeholder="Enter Description"
                        :state="errors[0] ? false : (valid ? true : null)"
                        rows="3"
                        max-rows="6"
                      ></b-form-textarea>
                    </b-input-group>
                      <!-- <span>Month</span> -->
                      <div class="d-block invalid-feedback">
                        {{ errors[0] }}
                      </div>
                    </b-form-group>
                  </ValidationProvider>
                </b-col>
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="Country" vid="country_id" rules="required" v-slot="{ errors }">
                      <b-form-group
                        label-for="country_id"
                      >
                      <template v-slot:label>
                        Country <span class="text-danger">*</span>
                      </template>
                      <treeselect
                        id="country_id"
                        :options="countryList"
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
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="Date of Birth" vid="dob" rules="required" v-slot="{ errors }">
                    <b-form-group
                      label-for="dob">
                      <template v-slot:label>
                        Date of Birth
                      </template>
                      <flat-pickr
                        id="dob"
                        v-model="form.dob"
                        class="form-control"
                        placeholder="Select Date of Birth"
                        :state="errors[0] ? false : (valid ? true : null)"
                        :config="flatPickrConfig"
                      />
                      <div class="d-block invalid-feedback">
                        {{ errors[0] }}
                      </div>
                    </b-form-group>
                  </ValidationProvider>
                </b-col>
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="Enlistment Date" vid="enlistment_date" rules="required" v-slot="{ errors }">
                    <b-form-group
                      label-for="enlistment_date">
                      <template v-slot:label>
                        Enlistment Date
                      </template>
                      <flat-pickr
                        id="enlistment_date"
                        v-model="form.enlistment_date"
                        class="form-control"
                        placeholder="Select Enlistment Date"
                        :state="errors[0] ? false : (valid ? true : null)"
                        :config="flatPickrConfig"
                      />
                      <div class="d-block invalid-feedback">
                        {{ errors[0] }}
                      </div>
                    </b-form-group>
                  </ValidationProvider>
                </b-col>
                <b-col lg="2" md="2" sm="12" xs="12">
                  <ValidationProvider name="Marital Status" vid="is_married" rules="required" v-slot="{ errors }">
                    <b-form-group
                      label-for="is_married">
                      <template v-slot:label>
                        Marital Status
                      </template>
                      <b-form-checkbox
                        id="is_married"
                        v-model="form.is_married"
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
                  <ValidationProvider name="Mobile" vid="mobile" rules="required|integer" v-slot="{ errors }">
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
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="Alternative Mobile" vid="alternative_mobile" rules="integer" v-slot="{ errors }">
                    <b-form-group
                      label-for="alternative_mobile"
                    >
                    <template v-slot:label>
                      Alternative Mobile
                    </template>
                    <b-input-group>
                        <b-form-input
                        id="alternative_mobile"
                        v-model="form.alternative_mobile"
                        placeholder="Enter Alternative Mobile (1724xxxxxxx)"
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
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="Email" vid="email" rules="required|email" v-slot="{ errors }">
                    <b-form-group
                      label-for="email"
                    >
                    <template v-slot:label>
                    Email <span class="text-danger">*</span>
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
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="Other Email" vid="other_email" rules="email" v-slot="{ errors }">
                    <b-form-group
                      label-for="other_email"
                    >
                    <template v-slot:label>
                      Other Email
                    </template>
                    <b-input-group>
                        <b-form-input
                        id="other_email"
                        v-model="form.other_email"
                        placeholder="Enter Other Email"
                        :state="errors[0] ? false : (valid ? true : null)"
                        ></b-form-input>
                    </b-input-group>
                      <div class="d-block invalid-feedback">
                        {{ errors[0] }}
                      </div>
                    </b-form-group>
                  </ValidationProvider>
                </b-col>
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="Lead Url" vid="lead_url" rules="" v-slot="{ errors }">
                    <b-form-group
                      label-for="lead_url"
                    >
                    <template v-slot:label>
                      Lead Url <span class="text-danger">*</span>
                    </template>
                    <b-input-group>
                        <b-form-input
                        type="url"
                        id="lead_url"
                        v-model="form.lead_url"
                        placeholder="Enter Lead Url"
                        :state="errors[0] ? false : (valid ? true : null)"
                        ></b-form-input>
                    </b-input-group>
                      <div class="d-block invalid-feedback">
                        {{ errors[0] }}
                      </div>
                    </b-form-group>
                  </ValidationProvider>
                </b-col>
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="Supervisor" vid="supervisor_id" rules="required" v-slot="{ errors }">
                      <b-form-group
                        label-for="supervisor_id"
                      >
                      <template v-slot:label>
                        Supervisor <span class="text-danger">*</span>
                      </template>
                      <treeselect
                        id="supervisor_id"
                        :options="userList"
                        placeholder="Select Supervisor"
                        v-model="form.supervisor_id"
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
                <b-col lg="2" md="2" sm="12" xs="12">
                  <ValidationProvider name="Is Client" vid="is_client" rules="required" v-slot="{ errors }">
                    <b-form-group
                      label-for="is_client">
                      <template v-slot:label>
                        Is Client
                      </template>
                      <b-form-checkbox
                        id="is_client"
                        v-model="form.is_client"
                        :state="errors[0] ? false : (valid ? true : null)"
                        switch>
                        </b-form-checkbox>
                      <div class="d-block invalid-feedback">
                        {{ errors[0] }}
                      </div>
                    </b-form-group>
                  </ValidationProvider>
                </b-col>
                <b-col class="mt-2 mb-2" lg="12" md="12" sm="12" xs="12">
                  <h6>Present Address: </h6>
                </b-col>
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="Address" vid="present_address" rules="required" v-slot="{ errors }">
                    <b-form-group
                      label-for="present_address"
                    >
                    <template v-slot:label>
                      Address <span class="text-danger">*</span>
                    </template>
                    <b-input-group>
                        <b-form-input
                        id="present_address"
                        v-model="form.present_address"
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
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="City" vid="pre_city" rules="required" v-slot="{ errors }">
                    <b-form-group
                      label-for="pre_city"
                    >
                    <template v-slot:label>
                      City <span class="text-danger">*</span>
                    </template>
                    <b-input-group>
                        <b-form-input
                        id="pre_city"
                        v-model="form.pre_city"
                        placeholder="Enter City"
                        :state="errors[0] ? false : (valid ? true : null)"
                        ></b-form-input>
                    </b-input-group>
                      <div class="d-block invalid-feedback">
                        {{ errors[0] }}
                      </div>
                    </b-form-group>
                  </ValidationProvider>
                </b-col>
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="Post Code" vid="pre_post_code" rules="required" v-slot="{ errors }">
                    <b-form-group
                      label-for="pre_post_code"
                    >
                    <template v-slot:label>
                      Post Code <span class="text-danger">*</span>
                    </template>
                    <b-input-group>
                        <b-form-input
                        id="pre_post_code"
                        v-model="form.pre_post_code"
                        placeholder="Enter Post Code"
                        :state="errors[0] ? false : (valid ? true : null)"
                        ></b-form-input>
                    </b-input-group>
                      <div class="d-block invalid-feedback">
                        {{ errors[0] }}
                      </div>
                    </b-form-group>
                  </ValidationProvider>
                </b-col>
                <b-col class="mt-2 mb-2" lg="12" md="12" sm="12" xs="12">
                  <h6>Permanent Address: </h6>
                </b-col>
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="Address" vid="permanent_address" rules="required" v-slot="{ errors }">
                    <b-form-group
                      label-for="permanent_address"
                    >
                    <template v-slot:label>
                      Address <span class="text-danger">*</span>
                    </template>
                    <b-input-group>
                        <b-form-input
                        id="permanent_address"
                        v-model="form.permanent_address"
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
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="City" vid="per_city" rules="required" v-slot="{ errors }">
                    <b-form-group
                      label-for="per_city"
                    >
                    <template v-slot:label>
                      City <span class="text-danger">*</span>
                    </template>
                    <b-input-group>
                        <b-form-input
                        id="per_city"
                        v-model="form.per_city"
                        placeholder="Enter City"
                        :state="errors[0] ? false : (valid ? true : null)"
                        ></b-form-input>
                    </b-input-group>
                      <div class="d-block invalid-feedback">
                        {{ errors[0] }}
                      </div>
                    </b-form-group>
                  </ValidationProvider>
                </b-col>
                <b-col lg="4" md="4" sm="12" xs="12">
                  <ValidationProvider name="Post Code" vid="per_post_code" rules="required" v-slot="{ errors }">
                    <b-form-group
                      label-for="per_post_code"
                    >
                    <template v-slot:label>
                      Post Code <span class="text-danger">*</span>
                    </template>
                    <b-input-group>
                        <b-form-input
                        id="per_post_code"
                        v-model="form.per_post_code"
                        placeholder="Enter Post Code"
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
            <div class="row mt-3">
              <div class="col-sm-3"></div>
              <div class="col text-right">
                  <b-button type="submit" size="sm" variant="primary" class="mr-2">{{ SaveButton }}</b-button>
                  &nbsp;
                  <!-- <b-button variant="danger" class="mr-1" @click="$bvModal.hide('modal-1')">Cancel</b-button> -->
                  <b-button @click="goToBackList()" size="sm" variant="danger" class="mr-1">Cancel</b-button>
              </div>
            </div>
            </b-form>
          </ValidationObserver>
          </b-card-text>
        </b-card>
      </div>
    </b-overlay>
  </div>
</template>
<script>
import RestApi, { baseURL } from '@/config'
// import the component
import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.css'

export default {
  props: ['editItem'],
  components: {
    Treeselect,
    flatPickr
  },
  data () {
    return {
      SaveButton: this.leadId ? 'Update' : 'Save',
      form: {
        lead_url: '',
        first_name: '',
        last_name: '',
        nick_name: '',
        country_id: null,
        dob: '',
        enlistment_date: '',
        is_married: false,
        mobile_country_id: null,
        mobile: '',
        alternative_mobile: '',
        email: '',
        other_email: '',
        present_address: '',
        permanent_address: '',
        per_city: '',
        pre_city: '',
        per_post_code: '',
        pre_post_code: '',
        supervisor_id: null,
        description: '',
        slug: '',
        source: '',
        is_client: false,
        active: true
      },
      flatPickrConfig: {
        // maxDate: 'today',
        dateFormat: 'd-m-Y'
      },
      errors: [],
      userList: [],
      valid: null,
      loading: false
    }
  },
  computed: {
    mobileCountryCodeList: function () {
      return this.countryList.map(item => {
        return {
          id: item.id,
          label: `${item.name}(+${item.calling_code})`
        }
      })
    },
    countryList: function () {
      return this.cn(this.$store.state.dropdowns, 'countries')
    }
  },
  watch: {
    'form.last_name': function (lastName) {
      const fullName = this.form.first_name + ' ' + lastName
      const fullNameLowerCase = fullName.toLowerCase()
      const fullNameSlug = fullNameLowerCase.replaceAll(' ', '-')
      this.form.slug = fullNameSlug
    }
  },
  created () {
    if (this.editItem) {
      this.form = JSON.parse(this.editItem)
    }
    this.leadId = this.hash_id(this.$route.params.id, false)[0]
  },
  mounted () {
    this.getUserList()
    if (this.hash_id(this.$route.params.id, false)[0]) {
      this.getLeadMangementList(this.leadId)
    }
  },
  methods: {
    goToBackList () {
      this.$router.push('/leads')
    },
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_lead_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_lead_data', this.form)
      }
      this.loading = false
      if (result.success) {
        this.$emit('reloadList', true)
        this.$emit('reloadLeadDetails', this.form.id)
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.$router.push({ path: '/leads' })
        // this.$bvModal.hide('modal-1')
      } else {
        this.$refs.form.setErrors(result.errors)
      }
    },
    async getUserList () {
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_user_all_list')
      if (result.success) {
        this.userList = result.data
      }
    },
    async getLeadMangementList (leadId) {
      const params = Object.assign({}, { id: leadId })
      var result = await RestApi.getData(baseURL, 'api/v1/admin/ajax/get_lead_list_id_wise', params)
      if (result.success) {
        this.form = result.data
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
