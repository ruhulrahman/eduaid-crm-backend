<template>
  <div class="home-component-wrapper">
    <div class="main-wrapper">
      <div class="main-content-section home-logo-wrapper">
        <Header :layout="'Home'"></Header>
      </div>
    </div>
    <div class="component-wrapper">
      <b-container fluid>
        <b-row>
            <b-col sm="12" md="4">
                <b-card class="mc-db-card" @click="goToComp()">
                  <div class="d-flex justify-content-start align-items-center">
                    <img class="mr-2 bms" src="../assets/icons/main-dashboard/bazar-monitoring.png" alt="">
                    <h5>Eduaid</h5>
                  </div>
                </b-card>
            </b-col>
        </b-row>
      </b-container>
    </div>
    <Footer></Footer>
  </div>
</template>
<script>
import Header from '../components/Header.vue'
import Footer from '../components/Footer.vue'

export default {
  name: 'Home',
  components: {
    Header,
    Footer
  },
  methods: {
    goToComp () {
      this.$router.push('/dashboard')
    }
  }
}
</script>
