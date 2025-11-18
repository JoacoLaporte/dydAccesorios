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
  
  const route = useRoute()
  const query = ref(route.query.q || '')
  const productos = ref([])
  
  onMounted(async () => {
    const categorias = ['accesorios', 'termos']
    const all = await Promise.all(categorias.map(c => import(`../data/${c}.json`)))
    productos.value = all.flatMap((d, i) =>
      d.default.map(p => ({ ...p, tipo: categorias[i] }))
    )
  })
  
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
  