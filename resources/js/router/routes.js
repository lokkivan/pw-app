export default [
  {
    path: '/',
    name: 'home',
    component: () => import('~/pages/home'),
    meta: { title: 'Home' }
  },
  {
    path: '/users',
    name: 'users',
    component: () => import('~/pages/users'),
    meta: { title: 'Users' }
  },
  {
    path: '/transactions',
    name: 'transactions',
    component: () => import('~/pages/transactions'),
    meta: { title: 'Transactions' }
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('~/pages/auth/login'),
    meta: { title: 'Login' }
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('~/pages/auth/register'),
    meta: { title: 'Register' }
  },
  {
    path: '*',
    component: () => import('~/pages/errors/404'),
    meta: { title: 'Register' }
  }
]
