function page (path) {
  return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default [
  {
    path: '/',
    name: 'home',
    component: page('home.vue'),
    meta: { title: 'Home' }
  },
  {
    path: '/users',
    name: 'users',
    component: page('users.vue'),
    meta: { title: 'Users' }
  },
  {
    path: '/transactions',
    name: 'transactions',
    component: page('transactions.vue'),
    meta: { title: 'Transactions' }
  },
  {
    path: '/login',
    name: 'login',
    component: page('auth/login.vue'),
    meta: { title: 'Login' }
  },
  {
    path: '/register',
    name: 'register',
    component: page('auth/register.vue'),
    meta: { title: 'Register' }
  },
  {
    path: '*',
    component: page('errors/404.vue'),
    meta: { title: 'Register' }
  }
]
