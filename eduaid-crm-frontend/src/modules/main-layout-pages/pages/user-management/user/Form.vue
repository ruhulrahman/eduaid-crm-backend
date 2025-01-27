<template>
  <b-overlay :show="loading">
   <div class="formBoder">
    <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
      <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
      <b-row>
        <b-col lg="6" md="6" sm="6" xs="12">
          <ValidationProvider name="Name" vid="name" rules="required" v-slot="{ errors }">
            <b-form-group
              id="name"
              label="Name"
              label-for="name"
            >
            <template v-slot:label>
              Name <span>*</span>
            </template>
              <b-form-input
                id="name"
                v-model="form.name"
                type="text"
                placeholder="Enter Name"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="6" xs="12">
          <ValidationProvider name="Email" vid="email" rules="required" v-slot="{ errors }">
            <b-form-group
              id="email"
              label="Email"
              label-for="Email"
            >
            <template v-slot:label>
              Email <span>*</span>
            </template>
              <b-form-input
                id="email"
                v-model="form.email"
                type="email"
                placeholder="Enter email"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="d-block invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="6" xs="12">
          <ValidationProvider name="Phone" vid="phone" rules="required|digits:11" v-slot="{ errors }">
            <b-form-group
              id="name"
              label="Name"
              label-for="name"
            >
            <template v-slot:label>
              Phone <span>*</span>
            </template>
              <b-form-input
                id="phone"
                v-model="form.phone"
                placeholder="Enter Phone Number"
                :state="errors[0] ? false : (valid ? true : null)"
                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="6" xs="12">
          <ValidationProvider name="Password" vid="password" v-slot="{ errors }">
            <b-form-group
              id="password"
              label="Password"
              label-for="password"
            >
            <template v-slot:label>
              Password <span></span>
            </template>
              <b-form-input
                id="password"
                type="password"
                v-model="form.password"
                placeholder="Enter Password"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <b-col lg="6" md="6" sm="6" xs="12">
          <ValidationProvider name="Confirm Password" vid="confirm_password" v-slot="{ errors }">
            <b-form-group
              id="confirm_password"
              label="Confirm Password"
              label-for="confirm_password"
            >
            <template v-slot:label>
              Confirm Password <span></span>
            </template>
              <b-form-input
                type="password"
                id="phone"
                v-model="form.confirm_password"
                placeholder="Enter Confirm Password"
                :state="errors[0] ? false : (valid ? true : null)"
              ></b-form-input>
              <div class="invalid-feedback">
                {{ errors[0] }}
              </div>
            </b-form-group>
          </ValidationProvider>
        </b-col>
        <!-- <b-col lg="6" md="6" sm="6" xs="12">
          <ValidationProvider name="User Type" vid="user_type" rules="required">
            <b-form-group
                label-for="user_type"
                slot-scope="{ valid, errors }"
            >
            <template v-slot:label>
               User Type <span>*</span>
            </template>
            <b-form-select
                plain
                v-model="form.user_type"
                :options="userTypeList"
                id="user_type"
                :state="errors[0] ? false : (valid ? true : null)"
            >
                <template v-slot:first>
                <b-form-select-option :value=null>Select</b-form-select-option>
                </template>
            </b-form-select>
            <div class="invalid-feedback">
                {{ errors[0] }}
            </div>
            </b-form-group>
        </ValidationProvider>
        </b-col> -->
        <b-col lg="6" md="6" sm="6" xs="12">
          <ValidationProvider name="Role" vid="role_id" rules="required">
            <b-form-group
                label-for="role_id"
                slot-scope="{ valid, errors }"
            >
            <template v-slot:label>
               Role <span>*</span>
            </template>
            <b-form-select
                plain
                v-model="form.role_id"
                :options="roleList"
                id="role_id"
                :state="errors[0] ? false : (valid ? true : null)"
            >
                <template v-slot:first>
                <b-form-select-option :value=null>Select</b-form-select-option>
                </template>
            </b-form-select>
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
    </div>
  </b-overlay>
</template>
<script>
import RestApi, { baseURL } from '@/config'
// import { permissionStore } from '../../../api/routes'

export default {
  props: ['editItem'],
  data () {
    return {
      SaveButton: this.editItem ? 'Update' : 'Save',
      form: {
        name: '',
        email: '',
        password: '',
        confirm_password: '',
        phone: '',
        // user_type: null,
        role_id: null
      },
      errors: [],
      valid: null,
      loading: false,
      roleList: []
    }
  },
  created () {
    if (this.editItem) {
      this.form = this.editItem
    }
  },
  mounted () {
    this.getRoleList()
  },
  computed: {
    userTypeList: function () {
      const list = [
        { value: 'admin', text: 'Admin' }
      ]
      return list
    }
  },
  methods: {
    async submitData () {
      this.loading = true
      let result = ''
      if (this.form.id) {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/update_user_data', this.form)
      } else {
        result = await RestApi.postData(baseURL, 'api/v1/admin/ajax/store_user_data', this.form)
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
    },
    getRoleList () {
      RestApi.getData(baseURL, 'api/v1/admin/ajax/get_all_role_list', null).then(response => {
        if (response.success) {
          var data = response.data
          this.roleList = data.filter(obj => obj.value !== null && obj.text !== null && obj.active === 1) // Exclude items with null values
            .map((obj, index) => {
              return { value: obj.value, text: obj.text }
            })
        } else {
          this.roleList = []
        }
      })
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
</style>
