<template>
  <q-header elevated class="navbar-gradient text-black">
    <q-toolbar class="q-px-lg">

      <!-- LOGO -->
      <router-link to="/" class="row items-center text-black no-decoration">
        <q-avatar size="70px">
          <img src="/icons/logoDyd.jpg" alt="Logo DYD Accesorios" />
        </q-avatar>

        <q-toolbar-title class="text-h6 q-ml-sm">
          DyD Accesorios
        </q-toolbar-title>
      </router-link>

    
      <q-space />

      <!-- COTIZACION DOLAR-->
      <div v-if="dolarBlue" class="row items-center text-caption q-mr-md">
        <span class="cotizacion-dolar">Cotización Dolar:<q-icon name="mdi-currency-usd" color="black" size="21px"/><b>{{ dolarBlue.venta }}</b></span>
      </div>

      <!-- BUSCADOR -->
      <q-input
        v-model="search"
        placeholder="Buscar productos..."
        dense
        outlined
        class="q-mr-md bg-white"
        style="max-width: 300px;border-radius: 7px;"
      >
        <template #append>
          <q-icon name="search" />
        </template>
      </q-input>

      <!-- ICONO CARRITO -->
      <q-btn flat dense round icon="shopping_cart" to="/cart">
      <q-badge
        v-if="cart.totalItems > 0"
        floating
        color="red"
        text-color="white"
      >
        {{ cart.totalItems }}
      </q-badge>
      </q-btn>

    </q-toolbar>
  </q-header>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { useCartStore } from 'src/stores/cartStore'
import { useRouter } from 'vue-router'

const cart = useCartStore()
const dolarBlue = ref(null)

const search = ref('')
const router = useRouter()

onMounted(async () => {
  try {
    const { data } = await axios.get('https://dolarapi.com/v1/dolares')
    dolarBlue.value = data.find((d) => d.nombre === 'Blue')
    console.log('Cotización dólar blue:', dolarBlue.value)
  } catch (error) {
    console.error('Error al obtener la cotización del dólar Blue:', error)
  }
})

watch(search, val => {
  if (val.trim().length > 0) {
    router.push({ path: '/search', query: { q: val } })
  } else {
    router.push({ path: '/' })
  }
})

</script>

<style scoped>
  .navbar-gradient {
    background: linear-gradient(
      180deg,
      #72b7ce 30%,
      #9ac97a 100%
    );
    /* #5eb6d3a4 30%,
      #5aa727be 100% */
  }

  .no-decoration {
    text-decoration: none;
    color: inherit;
  }

  .cotizacion-dolar{
    font-size: 15px;
    margin-top: 1px
}
</style>
