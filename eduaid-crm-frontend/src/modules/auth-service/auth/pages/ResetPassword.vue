<template>
  <b-container fluid class="px-0">
    <b-row align-v="center" no-gutters>
       <b-col md="8" class="d-none d-md-block">
           <!-- <div class="login-bg"></div> -->
       </b-col>
       <b-col sm="12" md="4" class="bg-light">
           <div class="login-wrapper">
             <b-card>
               <!-- <img class="img-fluid d-block mx-auto" width="90" src="@/assets/images/logo.png" alt=""> -->
               <h6 class="text-center mt-2 mb-4">Eduaid</h6>
               <b-overlay :show="loading">
                <ValidationObserver ref="form" v-slot="{ handleSubmit, reset }">
                  <b-form @submit.prevent="handleSubmit(resetPassword)" @reset.prevent="reset" autocomplete="off">
                    <ValidationProvider name="email" vid="email" rules="required" v-slot="{ errors }">
                      <b-form-group
                          id="email"
                          label="Email"
                          label-for="password"
                          >
                          <b-form-input
                          readonly
                          id="input-2"
                          v-model="form.email"
                          type="email"
                          placeholder="Enter email"
                          :state="errors[0] ? false : (valid ? true : null)"
                          required
                          ></b-form-input>
                      </b-form-group>
                    </ValidationProvider>
                    <ValidationProvider name="Password" vid="password" rules="required" v-slot="{ errors }">
                      <b-form-group
                          id="password"
                          label="Password"
                          label-for="password"
                          >
                          <b-form-input
                          id="input-2"
                          v-model="form.new_password"
                          type="password"
                          placeholder="Enter Password"
                          :state="errors[0] ? false : (valid ? true : null)"
                          required
                          ></b-form-input>
                      </b-form-group>
                    </ValidationProvider>
                    <ValidationProvider name="Passord Confirmation" vid="password_confirmation" rules="required" v-slot="{ errors }">
                      <b-form-group
                          id="password_confirmation"
                          label="Password Confirmation"
                          label-for="Password Confirmation"
                          >
                          <b-form-input
                          id="input-2"
                          v-model="form.password_confirmation"
                          type="password"
                          placeholder="Enter Password"
                          :state="errors[0] ? false : (valid ? true : null)"
                          required
                          ></b-form-input>
                      </b-form-group>
                    </ValidationProvider>
                    <b-button block type="submit" class="my-4" variant="primary">Set New Password</b-button>
                    <!-- <p>Don’t have an account?<router-link to="/sign-up"> Create an account</router-link></p> -->
                  </b-form>
                </ValidationObserver>
              </b-overlay>
             </b-card>
           </div>
       </b-col>
  </b-row>
  </b-container>
</template>
<script>
import RestApi, { baseURL } from '@/config'
import { changePassword } from '../api/routes'
export default {
  props: ['passwordResetCode'],
  data () {
    return {
      form: {
        email: '',
        new_password: '',
        password_confirmation: ''
      },
      loading: false,
      valid: null
    }
  },
  // created () {
  //   this.tokenWiseEmailGet()
  // },
  created () {
    var apiToken = localStorage.getItem('access_token')

    if (apiToken) {
      this.$router.replace('/dashboard')
    }
  },
  mounted () {
    this.tokenWiseEmailGet()
  },
  methods: {
    async resetPassword () {
      this.loading = true
      const result = await RestApi.postData(baseURL, changePassword, this.form)
      this.loading = false
      if (result.success) {
        this.$toast.success({
          title: 'Success',
          message: 'Reset completed successfully'
        })
        this.$router.push('/login')
        // this.loading = false
      } else {
        this.$refs.form.setErrors(result.errors)
        this.$toast.error({
          title: 'Error',
          message: 'Operation failed! Please, try again.'
        })
      }
    },
    async tokenWiseEmailGet () {
      var params = this.$route.params.token
      var result = await RestApi.getData(baseURL, `api/password-reset-email/${params}`)
      if (result.success) {
        this.form.email = result.data.email
        this.$toast.success({
          title: 'Success',
          message: result.message
        })
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
