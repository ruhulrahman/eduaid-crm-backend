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
                <h6 class="text-center mt-2 mb-4">ABC</h6>
                <b-form>
                    <b-form-group
                        id="user"
                        label="Email/ User Name"
                        label-for="user"
                    >
                        <b-form-input
                        id="userInput"
                        v-model="form.email"
                        type="email"
                        placeholder="Enter email"
                        required
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                    id="pass"
                    label="Password"
                    label-for="passInput"
                    description="Forgot Password?">
                        <b-form-input
                        id="input-2"
                        v-model="form.pass"
                        type="password"
                        placeholder="Enter Password"
                        required
                        ></b-form-input>
                    </b-form-group>
                    <b-button block type="submit" @click="goToDashboard()" class="my-4" variant="primary">Login</b-button>
                    <b-small>Don’t have an account? <a href="javascript:">SignUp</a></b-small>
                </b-form>
              </b-card>
            </div>
        </b-col>
   </b-row>
   </b-container>
</template>

<script>
export default {
  data () {
    return {
      form: {
        email: 'admin',
        pass: '12345678'
      }
    }
  },
  methods: {
    goToDashboard () {
      this.$router.push('/dashboard')
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
    background: linear-gradient(to bottom, rgb(50 50 50 / 35%), rgb(50 50 50 / 35%)),url(../../assets/images/login-bg.jpg);
    background-position: center;
    background-size: cover;
}
</style>
