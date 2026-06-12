import { defineStore } from 'pinia'
import { api } from 'src/boot/axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('admin_token') || null,
    user: null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    async login(email, password) {
      const { data } = await api.post('/admin/login', { email, password })
      this.token = data.token
      this.user = data.user
      localStorage.setItem('admin_token', data.token)
    },

    logout() {
      api.post('/admin/logout').catch(() => {})
      this.token = null
      this.user = null
      localStorage.removeItem('admin_token')
    },
  },
})
