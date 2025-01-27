<template>
  <b-container fluid class="px-0">
    <b-row align-v="center" no-gutters>
       <b-col md="8" class="d-none d-md-block">
           <!-- <div class="login-bg"></div> -->
       </b-col>
      <b-col sm="12" md="4" class="bg-light">
           <div class="login-wrapper">
            <b-overlay :show="loading">
              <b-card>
                <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
                <b-form @submit.prevent="handleSubmit(loginData)" @reset.prevent="reset" autocomplete="off">
                <!-- <img class="img-fluid d-block mx-auto" width="90" src="@/assets/images/logo.png" alt=""> -->
                <h6 class="text-center mt-2 mb-4">Eduaid</h6>
                    <b-col lg="12" md="12" sm="12" xs="12">
                      <ValidationProvider name="Identifier" vid="identifier" rules="required" v-slot="{ errors }">
                        <b-form-group
                            id="user"
                            label="Email/Phone"
                            label-for="user"
                        >
                          <b-form-input
                            id="email"
                            v-model="form.identifier"
                            type="text"
                            placeholder="Enter email or phone"
                            :state="errors[0] ? false : (valid ? true : null)"
                            ></b-form-input>
                            <div class="invalid-feedback">
                              {{ errors[0] }}
                            </div>
                        </b-form-group>
                      </ValidationProvider>
                    </b-col>
                    <b-col lg="12" md="12" sm="12" xs="12">
                      <ValidationProvider name="Password" vid="password" rules="required" v-slot="{ errors }">
                        <b-form-group
                            id="password"
                            label="Password"
                            label-for="password"
                        >
                            <b-form-input
                            id="password"
                            v-model="form.password"
                            type="password"
                            placeholder="Enter password"
                            :state="errors[0] ? false : (valid ? true : null)"
                            ></b-form-input>
                            <div class="invalid-feedback">
                              {{ errors[0] }}
                            </div>
                        </b-form-group>
                      </ValidationProvider>
                    </b-col>
                    <p class="forgot-text"><i class="ri-key-2-fill"></i><router-link to="/forgot-password">Forgot Password?</router-link></p>
                    <b-button block type="submit" class="my-4" variant="primary">Sign In</b-button>
                    <!-- <p>Don’t have an account?<router-link to="/sign-up"> Create an account</router-link></p> -->
                </b-form>
                </ValidationObserver>
              </b-card>
            </b-overlay>
           </div>
       </b-col>
  </b-row>
  </b-container>
</template>
<script>
// import config from '@/config'
import RestApi, { baseURL } from '@/config'
import { signInApi } from '../api/routes'
export default {
  data () {
    return {
      form: {
        identifier: '',
        password: ''
      },
      loading: false,
      errorAlert: false,
      errorMsg: '',
      errors: [],
      valid: null
    }
  },
  created () {
    var apiToken = localStorage.getItem('access_token')

    if (apiToken) {
      this.$router.replace('/dashboard')
      // this.$router.replace('/dashboard')
    }
  },
  methods: {
    async loginData () {
      this.loading = true
      const result = await RestApi.postData(baseURL, signInApi, this.form)
      this.loading = false
      if (result.success) {
        this.$toast.success({ title: 'Success', message: 'You logedin successfully' })
        localStorage.setItem('access_token', result.access_token)
        localStorage.setItem('api_token', result.access_token)
        this.$store.dispatch('Auth/setAccessToken', result.access_token)
        this.$store.dispatch('Auth/setAuthUser', result.user)
        this.$store.dispatch('Auth/setUserPermissions', result.user_permissions)
        this.$router.replace(sessionStorage.getItem('redirectPath') || '/dashboard')
        sessionStorage.removeItem('redirectPath')
      } else {
        this.$refs.form.setErrors(result.errors)
      }
      //  else {
      //   this.$toast.error({
      //     message: result.message
      //   })
      // }
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
