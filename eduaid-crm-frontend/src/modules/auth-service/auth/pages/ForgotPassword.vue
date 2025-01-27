<template>
    <b-container fluid class="px-0">
      <b-row align-v="center" no-gutters>
         <b-col md="8" class="d-none d-md-block">
             <!-- <div class="login-bg"></div> -->
         </b-col>
         <b-overlay :show="loading">
          <b-col sm="12" md="12" class="bg-light">
              <div class="login-wrapper">
                <b-card>
                  <!-- <img class="img-fluid d-block mx-auto" width="90" src="@/assets/images/logo.png" alt=""> -->
                  <h6 class="text-center mt-2 mb-4">Eduaid</h6>
                  <h6>Forgot Password?</h6>
                  <br>
                  <p style="font-size:13px">Enter your email and we'll send you instructions to reset your password</p>
                  <br>
                  <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
                    <b-form @submit.prevent="handleSubmit(forgotPassword)" @reset.prevent="reset" autocomplete="off">
                      <b-row>
                        <b-col lg="12" md="12" sm="12" xs="12">
                          <ValidationProvider name="Email" vid="email" rules="required" v-slot="{ errors }">
                            <b-form-group
                              id="user"
                              label="Email"
                              label-for="user"
                            >
                            <b-form-input
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="Enter email"
                            :state="errors[0] ? false : (valid ? true : null)"
                            ></b-form-input>
                            <div class="invalid-feedback">
                              {{ errors[0] }}
                            </div>
                            </b-form-group>
                          </ValidationProvider>
                        </b-col>
                      </b-row>
                      <b-button block type="submit" class="my-4" variant="primary">Send Reset Link</b-button>
                      <h6 style="text-align: center;"><router-link to="/login">Back to Login</router-link></h6>
                      <br>
                      <!-- <p>Don’t have an account?<router-link to="/sign-up"> Create an account</router-link></p> -->
                    </b-form>
                  </ValidationObserver>
                </b-card>
              </div>
          </b-col>
         </b-overlay>
    </b-row>
    </b-container>
  </template>
<script>
import RestApi, { baseURL } from '@/config'
import { emailSend } from '../api/routes'
export default {
  data () {
    return {
      form: {
        email: ''
      },
      loading: false,
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
    async forgotPassword () {
      this.loading = true
      const result = await RestApi.postData(baseURL, emailSend, this.form)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
        // this.$router.push('/reset-password')
      } else {
        this.$toast.error({
          title: 'Failed',
          message: result.message
        })
        this.$refs.form.setErrors(result.errors)
      }
    }
  }
}
</script>
  <style scoped>
  .login-wrapper{
     height: 100vh;
     display: flex;
     justify-content: center;
     align-items: center;
  }
  .login-wrapper .card {
   width: 25em;
  }
  .login-bg{
     width: 100%;
     height: 100vh;
     /* background: linear-gradient(to bottom, rgb(50 50 50 / 35%), rgb(50 50 50 / 35%)),url(../../assets/images/login-bg.jpg); */
     background-position: center;
     background-size: cover;
  }
  </style>
