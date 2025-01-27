<template>
   <b-container fluid class="px-0">
     <b-overlay :show="loading">
     <b-row align-v="center" no-gutters>
        <b-col cols="12" class="bg-secondary-signup">
            <div class="signup-wrapper mc-signup-bg">
              <b-card>
                <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
                  <b-form @submit.prevent="handleSubmit(submitData)" @reset.prevent="reset" autocomplete="off">
                  <!-- <img class="img-fluid d-block mx-auto" width="100" src="@/assets/images/logo.png" alt=""> -->
                  <h5 class="text-center mb-3 text-light">Eduaid</h5>
                  <hr class="text-light">
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
                            placeholder="Enter Email"
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
                  <b-col lg="6" md="6" sm="6" xs="12">
                    <ValidationProvider name="Phone" vid="phone" rules="required|digits:11" v-slot="{ errors }">
                      <b-form-group
                        id="phone"
                        label="Phone"
                        label-for="Phone"
                      >
                      <template v-slot:label>
                        Phone <span>*</span>
                      </template>
                        <b-form-input
                          id="phone"
                          v-model="form.phone"
                          type="phone"
                          placeholder="Enter Phone Number"
                          :state="errors[0] ? false : (valid ? true : null)"
                        ></b-form-input>
                        <div class="invalid-feedback">
                          {{ errors[0] }}
                        </div>
                      </b-form-group>
                    </ValidationProvider>
                  </b-col>
                  <b-col lg="6" md="6" sm="6" xs="12">
                    <ValidationProvider name="Password" vid="password" rules="required" v-slot="{ errors }">
                      <b-form-group
                        id="password"
                        label="Password"
                        label-for="Password"
                      >
                      <template v-slot:label>
                        Password <span>*</span>
                      </template>
                        <b-form-input
                          id="password"
                          v-model="form.password"
                          type="password"
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
                    <ValidationProvider name="Confirm Password" vid="c_password" rules="required" v-slot="{ errors }">
                      <b-form-group
                        id="c_password"
                        label="Password"
                        label-for="Password"
                      >
                      <template v-slot:label>
                        Confirm Password <span>*</span>
                      </template>
                        <b-form-input
                          id="c_password"
                          v-model="form.c_password"
                          type="password"
                          placeholder="Enter Password"
                          :state="errors[0] ? false : (valid ? true : null)"
                        ></b-form-input>
                        <div class="invalid-feedback">
                          {{ errors[0] }}
                        </div>
                      </b-form-group>
                    </ValidationProvider>
                  </b-col>
                  </b-row>
                  <b-button block type="submit" class="mb-2" variant="primary">SignUp</b-button>
                  <small class="text-light">Already have an account?<router-link to="/login" class="text-light"> Sign in instead</router-link></small>
                  </b-form>
                </ValidationObserver>
              </b-card>
            </div>
        </b-col>
      </b-row>
    </b-overlay>
   </b-container>
</template>
<script>
import Vue from 'vue'
import RestApi, { baseURL } from '@/config'
import { signUpApi } from '../api/routes'

// import CxltToastr from 'cxlt-vue2-toastr'
// Vue.use(CxltToastr)
import Notifications from 'vue-notification'
Vue.use(Notifications)

export default {
  data () {
    return {
      loading: false,
      form: {
        name: '',
        email: '',
        password: '',
        c_password: '',
        phone: ''
      },
      errors: [],
      valid: null
    }
  },
  created () {
    var apiToken = localStorage.getItem('access_token')

    if (apiToken) {
      this.$router.replace('/dashboard')
    }
  },
  methods: {
    async submitData () {
      const ref = this
      this.loading = true
      const result = await RestApi.postData(baseURL, signUpApi, this.form)
      this.loading = false
      if (result.success) {
        ref.$toast.success({ title: 'Success', message: result.message })
        ref.$router.push('/login')
      }
    }
  }
}
</script>
<style>
.mc-signup-bg{
  background: url(../../../../assets/images/sidebar-bg.png);
  background-position: center;
}
.signup-wrapper{
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.signup-wrapper .form-group label{
  color: var(--white);
}
.signup-wrapper .card{
  backdrop-filter: blur(2px);
  background: rgba(221, 207, 207, 0.1);
  border: 1px solid rgba(255, 255, 255, 1);
}
</style>
