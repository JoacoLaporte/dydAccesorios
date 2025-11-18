import { defineStore } from 'pinia'
import { ref, computed, watch } from 'vue'

export const useCartStore = defineStore('cart', () => {
  const carrito = ref([])

  // Al iniciar, cargar el carrito guardado (si existe)
  const carritoGuardado = localStorage.getItem('carrito')
  if (carritoGuardado) {
    carrito.value = JSON.parse(carritoGuardado)
  }

  // Guardar automáticamente cada vez que cambie
  watch(
    carrito,
    (nuevoCarrito) => {
      localStorage.setItem('carrito', JSON.stringify(nuevoCarrito))
    },
    { deep: true }
  )

  // Agregar producto al carrito
  function agregarProducto(producto) {
    // Usamos uid único (ej: "termo-1", "accesorio-5")
    const item = carrito.value.find(p => p.uid === producto.uid)

    if (item) {
      item.cantidad += producto.cantidad || 1
    } else {
      carrito.value.push({
        ...producto,
        precio: producto.precioARS,
        cantidad: producto.cantidad || 1
      })
    }
  }

  // Quitar producto por UID
  function quitarProducto(uid) {
    carrito.value = carrito.value.filter(p => p.uid !== uid)
  }

  // Incrementar cantidad
  function incrementarCantidad(uid) {
    const item = carrito.value.find(p => p.uid === uid)
    if (item) item.cantidad += 1
  }

  // Decrementar cantidad
  function decrementarCantidad(uid) {
    const item = carrito.value.find(p => p.uid === uid)
    if (item && item.cantidad > 1) item.cantidad -= 1
  }

  // Vaciar carrito (y limpiar localStorage)
  function vaciarCarrito() {
    carrito.value = []
    localStorage.removeItem('carrito')
  }

  // Total de ítems (suma de cantidades)
  const totalItems = computed(() =>
    carrito.value.reduce((acc, item) => acc + item.cantidad, 0)
  )

  // Total general del carrito (precio * cantidad)
  const totalCarrito = computed(() =>
    carrito.value.reduce((acc, item) => acc + item.precio * item.cantidad, 0)
  )

  // Agrupar productos por tipo (termos, accesorios, etc.)
  const productosAgrupados = computed(() => {
    const grupos = {}
    carrito.value.forEach(item => {
      if (!grupos[item.tipo]) grupos[item.tipo] = []
      grupos[item.tipo].push(item)
    })
    return grupos
  })

  return {
    carrito,
    agregarProducto,
    quitarProducto,
    incrementarCantidad,
    decrementarCantidad,
    vaciarCarrito,
    totalItems,
    totalCarrito,
    productosAgrupados
  }
})
