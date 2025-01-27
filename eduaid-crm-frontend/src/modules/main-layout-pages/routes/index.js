const childRoutes = (prop) => [
  {
    path: 'dashboard',
    name: prop + 'dashboard',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/Dashboard.vue')
  },
  {
    path: 'template-one',
    name: prop + 'template_one',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/template-one/List.vue')
  },
  {
    path: 'admin',
    name: prop + 'admin',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/admin/List.vue')
  },
  {
    path: 'cart',
    name: prop + 'cart',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/cart/List.vue')
  },
  {
    path: 'permission',
    name: prop + 'permission',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/user-management/permission/List.vue')
  },
  {
    path: 'role',
    name: prop + 'role',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/user-management/role/List.vue')
  },
  {
    path: 'add-or-update-role/:role_id?',
    name: prop + 'add_or_update_role',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/user-management/role/Form.vue')
  },
  {
    path: 'user',
    name: prop + 'user',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/user-management/user/List.vue')
  },
  {
    path: 'change-password',
    name: prop + 'change_password',
    meta: {
      auth: true,
      name: 'Editable'
    },
    component: () => import('../pages/security/ChangePassword.vue')
  },
  {
    path: 'services',
    name: prop + 'services',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/configurations/services/List.vue')
  },
  {
    path: 'service-statuses',
    name: prop + 'service_statuses',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/configurations/service-statuses/List.vue')
  },
  {
    path: 'point-questions-list-by-service-country/:service_id',
    name: prop + 'point_questions_by_service_country',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/configurations/point-questions/QuestionLIstByServiceCountry.vue')
  },
  {
    path: 'point-questions',
    name: prop + 'point_questions',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/configurations/point-questions/List.vue')
  },
  {
    path: 'course-levels',
    name: prop + 'course_levels',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/configurations/course-levels/List.vue')
  },
  {
    path: 'channels',
    name: prop + 'channels',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/configurations/channels/List.vue')
  },
  {
    path: 'language-tests',
    name: prop + 'language_tests',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/configurations/language-tests/List.vue')
  },
  {
    path: 'socials',
    name: prop + 'socials',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/configurations/socials/List.vue')
  },
  {
    path: 'leads',
    name: prop + 'leads',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/lead-management/leads/List.vue')
  },
  {
    path: 'add-lead/:id?',
    name: prop + 'add_lead',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/lead-management/leads/Form.vue')
  },
  {
    path: 'lead-details/:lead_id',
    name: prop + 'lead_details',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/lead-management/leads/Details.vue')
  },
  {
    path: 'status-groups',
    name: prop + 'status_groups',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/configurations/status-groups/List.vue')
  },
  {
    path: 'status-groups/:status_group_id',
    name: prop + 'statuses',
    meta: { auth: true, name: 'Editable' },
    component: () => import('../pages/configurations/status-groups/statuses/List.vue')
  }
]

const routes = [
  {
    path: '/',
    name: 'main_layout_pages',
    component: () => import('@/layouts/MainLayout.vue'),
    // meta: { auth: true },
    children: childRoutes('')
  }
]

export default routes
