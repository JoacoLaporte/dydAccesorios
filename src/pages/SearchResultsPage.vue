<template>
    <q-page padding>
      <h4>Resultados de búsqueda: "{{ query }}"</h4>
      <ProductsGrid :productos="filtrados" />
    </q-page>
  </template>
  
  <script setup>
  import { ref, onMounted, computed, watch } from 'vue'
  import { useRoute } from 'vue-router'
  import ProductsGrid from 'src/components/ProductsGrid.vue'
  import axios from 'axios'

  
  const route = useRoute()
  const query = ref(route.query.q || '')
  const productos = ref([])
  const dolarBlue = ref(null)


  onMounted(async () => {
  const dolarData = await axios.get('https://dolarapi.com/v1/dolares')
  dolarBlue.value = dolarData.data.find(d => d.nombre === 'Blue')

  const categorias = ['accesorios', 'termos', 'parlantes']
  const all = await Promise.all(categorias.map(c => import(`../data/${c}.json`)))

  productos.value = all.flatMap((d, i) =>
    d.default.map(p => ({
      ...p,
      tipo: categorias[i],
      precioARS: calcularPrecio(p)
    }))
  )
})

function calcularPrecio(p) {
  if (!dolarBlue.value) return p.precioARS ?? p.precio ?? 0
  
  // Si tiene precio en pesos, lo usamos. Si es en USD, lo convertimos.
  if (p.precio) {
    return Math.round(p.precio * dolarBlue.value.venta)
  }
  
  return p.precioARS
}

// Mantener query sincronizada con la URL
watch(() => route.query.q,
val => { query.value = val || '' },
{ immediate: true }
)

const filtrados = computed(() =>
  productos.value.filter(p =>
    p.nombre.toLowerCase().includes(query.value.toLowerCase())
  )
)
  </script>
  