import Vue from 'vue'
import VueRouter from 'vue-router'
// import ConfigurationRoute from '../modules/designer-service/configuration/routes'
import MainLayoutPagesRoute from '../modules/main-layout-pages/routes'
import AuthServiceAuthRoute from '../modules/auth-service/auth/routes'

Vue.use(VueRouter)

const defaultRoutes = [
  // {
  //   path: '/',
  //   name: 'Home',
  //   component: () => import(/* webpackChunkName: "home" */ '../views/Home.vue')
  // },
  // {
  //   path: '/',
  //   name: 'Login',
  //   component: () => import('../views/ui-elements/Login.vue')
  // },
  {
    path: '/',
    name: 'Login',
    component: () => import('../modules/auth-service/auth/pages/Login.vue')
  },
  {
    path: '/button',
    name: 'Button',
    component: () => import(/* webpackChunkName: "home" */ '../views/ui-elements/Button.vue')
  },
  {
    path: '/modal',
    name: 'Modal',
    component: () => import('../views/ui-elements/Modal.vue')
  },
  {
    path: '/breadcrumb',
    name: 'Breadcrumb',
    component: () => import('../views/ui-elements/Breadcrumb.vue')
  },
  {
    path: '/badge',
    name: 'Badge',
    component: () => import('../views/ui-elements/Badge.vue')
  },
  {
    path: '/tabs',
    name: 'Tabs',
    component: () => import('../views/ui-elements/Tabs.vue')
  },
  // {
  //   path: '/login',
  //   name: 'Login',
  //   component: () => import('../views/ui-elements/Login.vue')
  // },
  {
    path: '/signup',
    name: 'Signup',
    component: () => import('../views/ui-elements/Signup.vue')
  },
  {
    path: '/preloader',
    name: 'Preloader',
    component: () => import('../views/ui-elements/Preloader.vue')
  },
  {
    path: '/list-group',
    name: 'ListGroup',
    component: () => import('../views/ui-elements/ListGroup.vue')
  },
  {
    path: '/table',
    name: 'Table',
    component: () => import(/* webpackChunkName: "home" */ '../views/ui-elements/Table.vue')
  },
  {
    path: '/card',
    name: 'Card',
    component: () => import(/* webpackChunkName: "home" */ '../views/ui-elements/Card.vue')
  },
  {
    path: '/accordion',
    name: 'Accordion',
    component: () => import(/* webpackChunkName: "home" */ '../views/ui-elements/Accordion.vue')
  },
  {
    path: '/pagination',
    name: 'Pagination',
    component: () => import(/* webpackChunkName: "home" */ '../views/ui-elements/Pagination.vue')
  },
  {
    path: '/alert',
    name: 'Alert',
    component: () => import(/* webpackChunkName: "home" */ '../views/ui-elements/Alert.vue')
  },
  {
    path: '/tooltip',
    name: 'Tooltip',
    component: () => import(/* webpackChunkName: "home" */ '../views/ui-elements/Tooltip.vue')
  },
  {
    path: '/form',
    name: 'Form',
    component: () => import(/* webpackChunkName: "home" */ '../views/ui-elements/Form.vue')
  }
]

const routes = [
  ...defaultRoutes,
  ...AuthServiceAuthRoute,
  ...MainLayoutPagesRoute
]

const router = new VueRouter({
  mode: 'hash',
  base: process.env.BASE_URL,
  routes
})

export default router
