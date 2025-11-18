<template>
    <div class="row q-col-gutter-md justify-center">
      <div
        v-for="(producto, index) in productos"
        :key="index"
        class="col-12 col-sm-6 col-md-3"
      >
        <ProductsCard :producto="producto" />
      </div>
    </div>
  </template>
  
  <script setup>
  import ProductsCard from './ProductsCard.vue'
  defineProps({ productos: Array })
  </script>
  