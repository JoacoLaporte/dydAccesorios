<template>
  <q-page class="flex flex-center bg-grey-2">
    <q-card style="min-width: 360px" class="q-pa-md shadow-3">
      <q-card-section class="text-center q-pb-none">
        <div class="text-h5 text-weight-bold">DYD Accesorios</div>
        <div class="text-subtitle2 text-grey-6 q-mt-xs">Panel de administración</div>
      </q-card-section>

      <q-card-section>
        <q-form @submit.prevent="onSubmit" class="q-gutter-sm">
          <q-input
            v-model="email"
            label="Email"
            type="email"
            outlined
            :rules="[v => !!v || 'Requerido']"
          />
          <q-input
            v-model="password"
            label="Contraseña"
            type="password"
            outlined
            :rules="[v => !!v || 'Requerido']"
          />
          <div v-if="error" class="text-negative text-caption q-px-sm">{{ error }}</div>
          <q-btn
            type="submit"
            color="grey-9"
            label="Ingresar"
            class="full-width q-mt-sm"
            :loading="loading"
          />
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from 'src/stores/authStore'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

async function onSubmit() {
  loading.value = true
  error.value = ''
  try {
    await auth.login(email.value, password.value)
    router.push('/admin/productos')
  } catch (e) {
    error.value = e.response?.data?.errors?.email?.[0] || 'Error al iniciar sesión'
  } finally {
    loading.value = false
  }
}
</script>
