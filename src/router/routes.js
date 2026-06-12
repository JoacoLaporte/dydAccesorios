const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('src/pages/ProductsPage.vue') },
      { path: '/cart', component: () => import('src/pages/CartPage.vue') },
      { path: '/checkout', component: () => import('src/pages/CheckoutPage.vue') },
      { path: '/search', component: () => import('src/pages/SearchResultsPage.vue') },
    ],
  },
  {
    path: '/admin',
    component: () => import('src/layouts/AdminLayout.vue'),
    children: [
      { path: '', redirect: '/admin/productos' },
      { path: 'login', component: () => import('src/pages/admin/AdminLoginPage.vue') },
      {
        path: 'productos',
        component: () => import('src/pages/admin/AdminProductsPage.vue'),
        meta: { requiresAuth: true },
      },
    ],
  },
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
]

export default routes
