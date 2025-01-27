<template>
  <b-overlay :show="loading">
   <b-breadcrumb>
    <!-- <b-breadcrumb-item to="/dashboard">
      <b-icon icon="house-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
      Dashboard
    </b-breadcrumb-item> -->
    <!-- <b-breadcrumb-item active>Change Password</b-breadcrumb-item> -->
  </b-breadcrumb>
  <div class="formBoder">
    <ValidationObserver ref="form" v-slot="{ handleSubmit }">
      <b-form @submit.prevent="handleSubmit(submitData)">
      <b-row>
        <b-col lg="6" md="6" sm="6" xs="12" offset="2">
          <ValidationProvider name="Old Password" vid="old_password" rules="required|min:6">
            <b-form-group
              id="old_password"
              label="Password"
              label-for="password"
              slot-scope="{ valid, errors }"
            >
            <template v-slot:label>
              Old Password <span></span>
            </template>
              <b-form-input
                id="old_password"
                type="password"
                v-model="form.old_password"
                placeholder="Enter old password"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
      </b-row>
      <b-row>
        <b-col lg="6" md="6" sm="6" xs="12" offset="2">
          <ValidationProvider name="New Password" vid="new_password" rules="required|min:6">
            <b-form-group
              id="new_password"
              label="new_password"
              label-for="new_password"
              slot-scope="{ valid, errors }"
            >
            <template v-slot:label>
              New Password <span></span>
            </template>
              <b-form-input
                id="new_password"
                type="password"
                v-model="form.new_password"
                placeholder="Enter new password"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
      </b-row>
      <b-row>
        <b-col lg="6" md="6" sm="6" xs="12"  offset="2">
          <ValidationProvider name="Confirm Password" vid="confirm_password" rules="required|min_value:6">
            <b-form-group
              id="confirm_password"
              label="confirm_password"
              label-for="confirm_password"
              slot-scope="{ valid, errors }"
            >
            <template v-slot:label>
              Confirm Password <span></span>
            </template>
              <b-form-input
                type="password"
                id="confirm_password"
                v-model="form.confirm_password"
                placeholder="Enter confirm password"
                :state="errors[0] ? false : (valid ? true : null)"
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
            <b-button type="submit" variant="primary" class="mr-2">Submit</b-button>
            &nbsp;
            <b-button variant="danger" class="mr-1" @click="resetForm">Reset</b-button>
        </div>
      </div>
     </b-form>
    </ValidationObserver>
    </div>
  </b-overlay>
</template>
<script>
import RestApi, { baseURL } from '@/config'

export default {
  props: ['editItem'],
  data () {
    return {
      form: {
        old_password: '',
        new_password: '',
        confirm_password: ''
      },
      errors: [],
      valid: null,
      loading: false
    }
  },
  created () {
    if (this.editItem) {
      this.form = this.editItem
    }
  },
  mounted () {
  },
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/change_password_data', this.form)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        this.$bvModal.hide('modal-1')
        this.form = {
          old_password: '',
          new_password: '',
          confirm_password: ''
        }
      } else {
        this.$refs.form.setErrors(result.errors)
      }
    },
    resetForm () {
      this.form = {
        old_password: '',
        new_password: '',
        confirm_password: ''
      }
    }
  }
}
</script>
<style>
.formBoder {
    border: 1px solid;
    margin: 0px;
    padding: 35px;
    font-size: 13px
 }
</style>
