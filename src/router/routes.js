const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('src/pages/ProductsPage.vue') },
      { path: '/cart', component: () => import('src/pages/CartPage.vue') },
      { path: '/checkout', component: () => import('src/pages/CheckoutPage.vue') },
      { path: '/search', component: () => import('src/pages/SearchResultsPage.vue')
      }
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
]

export default routes
