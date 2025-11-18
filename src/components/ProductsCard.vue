<template>
    <q-card class="my-card column justify-between q-pa-sm shadow-2 rounded-borders">
  
      <!-- Imagen -->
      <q-img
        :src="producto.imagen"
        :alt="producto.descripcion"
        height="180px"
        class="rounded-borders"
      />
  
      <!-- Descripción -->
      <q-card-section class="q-pa-sm text-center">
        <div class="text-caption text-grey-7">{{ producto.codigo }}</div>
        <div class="text-subtitle1 text-weight-bold">{{ producto.descripcion }}</div>
        <div class="text-subtitle1 q-mt-sm">{{ producto.nombre }}</div>
        <div class="text-bold text-green">$ {{ producto.precioARS }}</div>
      </q-card-section>
  
      <!-- Cantidad -->
      <q-card-actions align="center" class="q-gutter-sm">
        <q-btn
          flat
          round
          color="black"
          icon="remove"
          @click="decrementarCantidad"
        />
  
        <q-input
          v-model.number="cantidad"
          type="number"
          outlined
          dense
          min="1"
          class="text-center cantidad-input"
          style="width: 70px"
        />
  
        <q-btn
          flat
          round
          color="black"
          icon="add"
          @click="incrementarCantidad"
        />
      </q-card-actions>
  
      <!-- Agregar al carrito -->
      <q-card-actions align="center">
        <q-btn
          color="black"
          icon="shopping_cart"
          label="Agregar"
          class="full-width"
          @click="agregarAlCarrito"
        />
      </q-card-actions>
  
    </q-card>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import { useQuasar } from 'quasar'
  import { useCartStore } from 'src/stores/cartStore'
  
  const props = defineProps({
    producto: {
      type: Object,
      required: true
    }
  })
  
  const $q = useQuasar()
  const cart = useCartStore()
  const cantidad = ref(1)
  
  function incrementarCantidad() {
    cantidad.value++
  }
  
  function decrementarCantidad() {
    if (cantidad.value > 1) cantidad.value--
  }
  
  function agregarAlCarrito() {
    if (cantidad.value < 1) {
      $q.notify({
        message: 'La cantidad debe ser al menos 1',
        color: 'negative',
        icon: 'warning',
        position: 'top',
        timeout: 1000
      })
      return
    }
  
    cart.agregarProducto({
      ...props.producto,
      cantidad: cantidad.value
    })
  
    $q.notify({
      message: `${props.producto.descripcion} agregado al carrito`,
      color: 'black',
      icon: 'check',
      iconColor: 'green',
      position: 'top',
      timeout: 1000
    })
  
    cantidad.value = 1
  }
  </script>
  
  <style scoped>
  .my-card {
    transition: transform 0.15s ease, box-shadow 0.15s ease;
  }
  .my-card:hover {
    transform: scale(1.02);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
  }
  .cantidad-input input {
    text-align: center;
  }
  </style>
  