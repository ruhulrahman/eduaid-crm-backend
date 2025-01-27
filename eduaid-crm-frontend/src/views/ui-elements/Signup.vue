<template>
   <b-container fluid class="px-0">
     <b-row align-v="center" no-gutters>
        <b-col cols="12" class="bg-light">
            <div class="login-wrapper">
              <b-card>
                <b-form>
                <img class="img-fluid d-block mx-auto" width="100" src="@/assets/images/logo.png" alt="">
                <h5 class="text-center mb-3">ABC Test Page</h5>
                <hr>
                <b-row>
                  <b-col>
                    <b-form-group
                        id="name"
                        label="Name"
                        label-for="name"
                    >
                        <b-form-input
                        id="nameInput"
                        v-model="form.name"
                        type="text"
                        placeholder="Enter Name"
                        required
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                    id="pass"
                    label="Password"
                    label-for="pass">
                        <b-form-input
                        id="passInput"
                        v-model="form.pass"
                        type="password"
                        placeholder="Enter Password"
                        required
                        ></b-form-input>
                    </b-form-group>
                  </b-col>
                  <b-col>
                    <b-form-group
                        id="email"
                        label="Email"
                        label-for="email"
                    >
                        <b-form-input
                        id="emailInput"
                        v-model="form.email"
                        type="email"
                        placeholder="Enter email"
                        required
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                    id="cPass"
                    label="Confirm Password"
                    label-for="cPass">
                        <b-form-input
                        id="cPass"
                        v-model="form.cPass"
                        type="password"
                        placeholder="Enter Password"
                        required
                        ></b-form-input>
                    </b-form-group>
                  </b-col>
                </b-row>
                <b-button block type="submit" class="mb-2" variant="primary">SignUp</b-button>
                <b-small>Already have an account? <a href="javascript:">Sign in</a></b-small>
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
        name: '',
        email: '',
        pass: '',
        cPass: ''
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
.login-bg{
    width: 100%;
    height: 100vh;
    background: linear-gradient(to bottom, rgb(50 50 50 / 35%), rgb(50 50 50 / 35%)),url(/img/login-bg.7b992b70.jpg);
    background-position: center;
    background-size: cover;
}
hr:not([size]) {
    height: 0;
}
</style>
