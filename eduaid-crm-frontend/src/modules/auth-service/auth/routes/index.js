const childRoutes = (prop) => [
  {
    path: 'login',
    name: prop + 'login',
    meta: { auth: false, name: 'Editable' },
    component: () => import('../pages/Login.vue')
  },
  {
    path: 'sign-up',
    name: prop + 'sign_up',
    meta: { auth: false, name: 'Editable' },
    component: () => import('../pages/SignUp.vue')
  },
  {
    path: 'forgot-password',
    name: prop + 'forgot_password',
    meta: { auth: false, name: 'Editable' },
    component: () => import('../pages/ForgotPassword.vue')
  },
  {
    path: 'reset-password/:token',
    name: prop + 'reset_password',
    meta: { auth: false, name: 'Editable' },
    component: () => import('../pages/ResetPassword.vue')
  }
]
const routes = [
  {
    // path: '/auth-service/auth',
    path: '/',
    name: 'auth_service.auth',
    component: () => import('@/layouts/AuthLayout.vue'),
    meta: { auth: false },
    // children: childRoutes('auth_service.auth')
    children: childRoutes('')
  }
]

export default routes
