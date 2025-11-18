<template>
  <q-page padding>
    <div class="q-pa-md">

      <!-- Tabs de categorías -->
      <q-tabs
        v-model="tab"
        dense
        class="text-black q-mb-md"
        active-color="green"
        indicator-color="green"
        narrow-indicator
        outside-arrows
        mobile-arrows
      >
        <q-tab name="accesorios" label="Accesorios" />
        <q-tab name="electronica" label="Electrónica" />
        <q-tab name="fundas" label="Fundas" />
        <q-tab name="termos" label="Termos" />
      </q-tabs>
  
      <!--  Grilla de productos -->
      <h4 class="titulo">{{ tab }}</h4>
      

      <div class="q-mt-md">
        <ProductsGrid :productos="productos" />
      </div>

    </div>
  </q-page>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'
import ProductsGrid from 'src/components/ProductsGrid.vue'

const tab = ref('accesorios')
const productos = ref([])
const dolarBlue = ref(null)

async function obtenerDolar() {
  try {
    const { data } = await axios.get('https://dolarapi.com/v1/dolares')
    dolarBlue.value = data.find(d => d.nombre === 'Blue')
  } catch (err) {
    console.error('Error al obtener el dólar:', err)
    dolarBlue.value = { venta: 1 }
  }
}

async function cargarProductos(categoria) {
  try {
    const data = await import(`../data/${categoria}.json`)
    const cotizacion = dolarBlue.value?.venta || 1

    productos.value = data.default
      .map((p, index) => {
        const precioConvertido =
          categoria === 'fundas'
            ? p.precio // precio fijo, sin dólar
            : (p.precio * cotizacion).toFixed(0) // convertido a pesos

        return {
          ...p,
          tipo: categoria,
          uid: `${categoria}-${p.id || index + 1}`,
          precioARS: precioConvertido
        }
      })
      .sort((a, b) => a.nombre.localeCompare(b.nombre))
  } catch (error) {
    console.error(`Error cargando productos de ${categoria}:`, error)
    productos.value = []
  }
}

onMounted(async () => {
  await obtenerDolar()
  await cargarProductos(tab.value)
})

watch(tab, (nuevaCategoria) => cargarProductos(nuevaCategoria))
</script>


<style scoped>
  .q-page {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  padding: 20px;
  box-sizing: border-box;
}


  .titulo{
    text-transform: capitalize;
  }

</style>
