<template>
    <div>
      <b-navbar toggleable="lg" class="fixed-nav">
        <b-navbar-brand  v-if="layout !== 'Home'" href="javascript:" class="d-none d-md-block sidebar-collapse-icon-wrapper" @click="toggleNav()">
          <i class="ri-menu-3-fill"></i>
        </b-navbar-brand>
        <b-navbar-brand v-b-toggle.sidebar-1 v-if="layout !== 'Home'"  href="javascript:" class="d-sm-block d-md-none sidebar-collapse-icon-wrapper">
          <i class="ri-menu-3-fill"></i>
        </b-navbar-brand>
        <b-navbar-brand href="javascript:" class="logo-wrapper">
          <img v-if="layout === 'Home'" src="../assets/images/book.png" class="mr-2" width="42" alt="">
          <span>Welcome to Eduaid Admin Panel<span class="sub-brand"></span></span>
        </b-navbar-brand>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav>
          <b-navbar-nav class="ml-auto">
            <!-- <LangButton /> -->
            <!-- <b-nav-item-dropdown class="notification-wrapper" right>
              <template #button-content>
                <div>
                  <i class="ri-notification-3-fill"></i>
                  <span class="notifi-counter">10</span>
                </div>
              </template>
              <p class="user-disc">Notifications <span class="notification-counter">10</span></p>
              <b-dropdown-item href="#">
                <div class="notification-body">
                  <img class="notification-img" src="../assets/images/user.jpg" alt="img">
                  <div class="notification-disc">
                    <p class="notify-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sed, assumenda.</p>
                    <small class="date-time">5 Min Ago</small>
                  </div>
                </div>
              </b-dropdown-item>
              <b-dropdown-item href="#">
                <div class="notification-body">
                  <img class="notification-img" src="../assets/images/user.jpg" alt="img">
                  <div class="notification-disc">
                    <p class="notify-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sed, assumenda.</p>
                    <small class="date-time">5 Min Ago</small>
                  </div>
                </div>
              </b-dropdown-item>
              <b-dropdown-item href="#">
                <div class="notification-body">
                  <img class="notification-img" src="../assets/images/user.jpg" alt="img">
                  <div class="notification-disc">
                    <p class="notify-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sed, assumenda.</p>
                    <small class="date-time">5 Min Ago</small>
                  </div>
                </div>
              </b-dropdown-item>
              <b-dropdown-item href="#">
                <div class="notification-body">
                  <img class="notification-img" src="../assets/images/user.jpg" alt="img">
                  <div class="notification-disc">
                    <p class="notify-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sed, assumenda.</p>
                    <small class="date-time">5 Min Ago</small>
                  </div>
                </div>
              </b-dropdown-item>
              <b-dropdown-item href="#">
                <div class="notification-body">
                  <img class="notification-img" src="../assets/images/user.jpg" alt="img">
                  <div class="notification-disc">
                    <p class="notify-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sed, assumenda.</p>
                    <small class="date-time">5 Min Ago</small>
                  </div>
                </div>
              </b-dropdown-item>
              <b-dropdown-item href="#">
                <div class="notification-body">
                  <img class="notification-img" src="../assets/images/user.jpg" alt="img">
                  <div class="notification-disc">
                    <p class="notify-content">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sed, assumenda.</p>
                    <small class="date-time">5 Min Ago</small>
                  </div>
                </div>
              </b-dropdown-item>
              <b-dropdown-item href="#" class="see-more-btn">See More</b-dropdown-item>
            </b-nav-item-dropdown> -->

            <b-nav-item-dropdown right>
              <template #button-content>
                <div class="user-dropdown-wrapper">
                  <!-- <img src="../assets/images/user.jpg" class="profile-img" alt=""> -->
                  <img v-if="cn(authUser, 'avatar')" :src="cn(authUser, 'avatar')" class="profile-img" alt="">
                  <img v-else src="../assets/images/user.jpg" class="profile-img" alt="">
                  <div class="user-name-wrapper">
                    <p class="user-name">{{ cn(authUser, 'name') }}</p>
                    <small class="user-role text-white">{{ cn(authUser, 'role.name', 'Admin') }}</small>
                  </div>
                </div>
              </template>
              <!-- <p class="user-disc">{{ cn(authUser, 'name') }}</p> -->
              <b-dropdown-item href="#" @click="openProfileModal()"><i class="ri-user-3-line"></i> Profile</b-dropdown-item>
              <!-- <b-dropdown-item href="#"><i class="ri-tools-fill"></i> Settings</b-dropdown-item> -->
              <!-- <b-dropdown-item href="#" to="/change-password"><i class="ri-lock-password-line"></i> Change Password</b-dropdown-item> -->
              <b-dropdown-item href="#" @click="openChangePasswordModal()"><i class="ri-lock-password-line"></i> Change Password</b-dropdown-item>
              <!-- <router-link  @click="goToLoginPage()"><b-dropdown-item href="#" class="log-out-btn"><i class="ri-logout-circle-r-line"></i> Log Out</b-dropdown-item></router-link> -->
              <b-dropdown-item href="#" @click="goToLoginPage()" class="log-out-btn"><i class="ri-logout-circle-r-line"></i> Log Out</b-dropdown-item>
            </b-nav-item-dropdown>
          </b-navbar-nav>
        </b-collapse>
      </b-navbar>
      <b-sidebar id="sidebar-1" shadow backdrop>
        <div class="sidebar-section w-100 show-mobile d-sm-block d-md-none">
          <Sidebar></Sidebar>
        </div>
      </b-sidebar>
      <b-modal id="modal-1" ref="changePasswordModal" size="lg" title="Change Password" hide-backdrop content-class="shadow" :hide-footer="true">
        <ChangePassword/>
      </b-modal>
      <b-modal id="modal-1" ref="profileModal" size="lg" title="Profile" hide-backdrop content-class="shadow" :hide-footer="true">
        <Profile/>
      </b-modal>
    </div>
</template>
<script>
import { EventBus } from '@/EventBusLayout'
import Sidebar from '../components/Sidebar.vue'
import ChangePassword from '@/modules/main-layout-pages/pages/security/ChangePassword'
import Profile from '@/modules/main-layout-pages/pages/security/Profile'
// import LangButton from '../components/LangButton.vue'
export default {
  Name: 'Navbar',
  props: ['layout'],
  data () {
    return {
      toggle: false
    }
  },
  components: {
    Sidebar,
    ChangePassword,
    Profile
  },
  computed: {
    authUser: function () {
      return this.$store.state.Auth.authUser
    },
    avatarUrl: function () {
      return 'http://book-management-api.local/storage/app/public/264fb13dd348ae.jpg'
    }
  },
  methods: {
    openChangePasswordModal () {
      this.$refs.changePasswordModal.show()
    },
    openProfileModal () {
      this.$refs.profileModal.show()
    },
    toggleNav () {
      this.toggle = !this.toggle
      EventBus.$emit('toggleNav', this.toggle)
    },
    goToLoginPage () {
      localStorage.removeItem('access_token')
      this.$store.dispatch('Auth/setAuthUser', '')
      this.$router.push('/login')
    }
  }
}
</script>
