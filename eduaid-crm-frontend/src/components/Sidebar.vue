<template>
    <div class="sidebar-wrapper">
        <a href="javascript:" class="logo-route">
            <div class="logo-wrapper">
                <!-- <img src="../assets/images/book.png" class="logo-img" alt=""> -->
                <h2 class="title">Eduaid</h2>
            </div>
        </a>
        <div class="sidebar-navigation">
            <CollapseMenu :items="items" :open="true" :horizontal="horizontal" :sidebarGroupTitle="sidebarGroupTitle"/>
            <ul>
                <li>
                    <router-link to="/dashboard">
                        <a href="">
                            <div>
                                <svg id="Layer_1" data-name="Layer 1"
                                    viewBox="0 0 100 100" style="fill:#fff;">
                                    <path class="cls-1" d="M50,21.25l25,22.5V82.8H65v-30H35v30H25v-39l25-22.5M50,7.8,2.87,50.21a1.49,1.49,0,0,0,1,2.59H15v40H45v-30H55v30H85v-40H96.13a1.49,1.49,0,0,0,1-2.59Z"/>
                                </svg>
                            </div>
                            Dashboard
                        </a>
                    </router-link>
                </li>
                <li v-if="has_permission('user_management')" class="submenu" style="margin-left: 10px;">
                    <a href="#">
                      <i class="ri-folder-user-fill pr-2"></i>
                       User Management
                        <i class="ri-arrow-down-s-line cus-arrow"></i>
                    </a>
                    <ul class="sub-menu">
                        <li v-if="has_permission('permission')" style="margin-left: 50px;">
                            <router-link to="/permission">
                               <p>Permission</p>
                            </router-link>
                        </li>
                        <li v-if="has_permission('role')" style="margin-left: 50px;">
                            <router-link to="/role">
                                <p>Role</p>
                            </router-link>
                        </li>
                        <li v-if="has_permission('user')" style="margin-left: 50px;">
                            <router-link to="/user">
                                <p>User</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li v-if="has_permission('permitted')" class="submenu" style="margin-left: 10px;">
                    <a href="#">
                      <i class="ri-list-settings-line pr-2"></i>
                       Configurations
                        <i class="ri-arrow-down-s-line cus-arrow"></i>
                    </a>
                    <ul class="sub-menu">
                        <li v-if="has_permission('permitted')" style="margin-left: 50px;">
                            <router-link to="/services">
                               <p>Services</p>
                            </router-link>
                        </li>
                        <li v-if="has_permission('permitted')" style="margin-left: 50px;">
                            <router-link to="/service-statuses">
                               <p>Service Statuses</p>
                            </router-link>
                        </li>
                        <li v-if="has_permission('permitted')" style="margin-left: 50px;">
                            <router-link to="/point-questions">
                               <p>Point Questions</p>
                            </router-link>
                        </li>
                        <li v-if="has_permission('permitted')" style="margin-left: 50px;">
                            <router-link to="/course-levels">
                               <p>Course Levels</p>
                            </router-link>
                        </li>
                        <li v-if="has_permission('permitted')" style="margin-left: 50px;">
                            <router-link to="/channels">
                               <p>Channels</p>
                            </router-link>
                        </li>
                        <li v-if="has_permission('permitted')" style="margin-left: 50px;">
                            <router-link to="/language-tests">
                               <p>Language Tests</p>
                            </router-link>
                        </li>
                        <li v-if="has_permission('permitted')" style="margin-left: 50px;">
                            <router-link to="/socials">
                               <p>Socials</p>
                            </router-link>
                        </li>
                        <li v-if="has_permission('permitted')" style="margin-left: 50px;">
                            <router-link to="/status-groups">
                               <p>Status Groups</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li v-if="has_permission('permitted')" class="submenu" style="margin-left: 10px;">
                    <a href="#">
                      <!-- <i class="ri-folder-user-fill pr-2"></i> -->
                      <i class="ri-building-line pr-2"></i>
                       Lead Management
                        <i class="ri-arrow-down-s-line cus-arrow"></i>
                    </a>
                    <ul class="sub-menu">
                        <li v-if="has_permission('permitted')" style="margin-left: 50px;">
                            <router-link to="/leads">
                               <p>Leads</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                <!-- <li v-if="has_permission('subscription_plan')">
                    <router-link to="/subscription-plan">
                        <a href="">
                          <i class="ri-building-3-fill pr-2"></i>
                        Subscription Plan
                        </a>
                    </router-link>
                </li>
                <li v-if="has_permission('subscriber')">
                  <router-link to="/subscriber">
                    <a href="">
                      <i class="ri-team-fill pr-2"></i>
                       Subscriber
                    </a>
                  </router-link>
                </li> -->
            </ul>
        </div>
    </div>
</template>
<script>
import CollapseMenu from './menus/CollapseMenu.vue'
export default {
  Name: 'Sidebar',
  components: {
    CollapseMenu
  },
  mounted () {
  },
  data () {
    return {
      items: [],
      homeURL: { type: Object, default: () => ({ path: '/auth/dashboard' }) },
      logo: { type: String, default: require('../assets/images/logo.png') },
      horizontal: true,
      toggleButton: true,
      logoShow: true,
      onlyLogo: true,
      onlyLogoText: true,
      sidebarGroupTitle: true,
      menus: [
        {
          name: 'Module',
          children: [
            {
              name: 'Service',
              children: [
                {
                  name: 'Item One'
                },
                {
                  name: 'Item Two'
                },
                {
                  name: 'Item Three'
                }
              ]
            }
          ]
        }
      ]
    }
  }
}
</script>
<style>
/* Your custom styles here */
.sub-menu {
  display: none;
  list-style: none;
}

.submenu:hover .sub-menu {
  display: block;
}
</style>
