<template>
  <q-layout class="main-layout" view="hHh lpR Fff">
    <!-- Header -->
    <MainNavbar />

    <!-- Contenido principal -->
    <q-page-container class="page-container">
      <router-view />
    </q-page-container>

    <!-- Footer -->
    <MainFooter />
  </q-layout>
</template>

<script setup>
import MainFooter from 'src/components/MainFooter.vue'
import MainNavbar from 'src/components/MainNavbar.vue'
</script>

<style scoped>
.main-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh; /* ocupa toda la pantalla */
}

.page-container {
  flex: 1; /* empuja el footer hacia abajo */
  display: flex;
  flex-direction: column;
}
</style>
