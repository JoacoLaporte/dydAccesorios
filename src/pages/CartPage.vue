<template>
  <q-page class="q-pa-md carrito-page">
    <div class="row q-col-gutter-lg">

      <!-- Lista de productos -->
      <div class="col-12 col-md-8">
        <h4 class="titulo">Mi carrito</h4>

        <!-- Si el carrito está vacío -->
        <div v-if="!cart.carrito.length" class="empty-cart q-pa-xl text-center">
          <q-icon name="shopping_cart" size="64px" color="negative" />
          <div class="text-h6 text-bold text-red-9 q-mt-sm">
            Tu carrito está vacío
          </div>
    
          <q-btn
            outline
            color="black"
            label="SEGUÍ COMPRANDO"
            no-caps
            class="q-mt-md text-bold"
            @click="$router.push('/')"
          />
        </div>

        <!-- Si hay productos -->
        <div v-else class="cart-card q-pa-md">
          <div
            v-for="(items, tipo) in cart.productosAgrupados"
            :key="tipo"
            class="q-mb-md"
          >
            <h6 class="text-black text-uppercase q-mb-md">{{ tipo }}</h6>
            <hr>

            <div
              v-for="item in items"
              :key="item.uid"
              class="cart-item row items-center q-py-sm q-mb-sm"
            >
              <!-- Imagen -->
              <div class="col-auto">
                <q-img
                  :src="item.imagen ? `${apiUrl}/storage/${item.imagen}` : '/icons/logoDyd.jpg'"
                  :alt="item.nombre"
                  style="width: 80px; height: 80px; border-radius: 8px;"
                />
              </div>

              <!-- Detalle del producto -->
              <div class="col q-pl-md">
                <div class="text-subtitle1 text-bold">{{ item.nombre }}</div>
                <div>
                  <q-btn
                    flat
                    label="Eliminar"
                    color="negative"
                    size="sm"
                    class="q-pa-none q-mt-xs"
                    @click="cart.quitarProducto(item.uid)"
                  />
                </div>
              </div>

              <!-- Controles de cantidad -->
              <div class="col-auto flex items-center cantidad-box">
                <!-- Botón menos -->
                <q-btn
                  flat
                  dense
                  round
                  icon="remove"
                  color="negative"
                  @click="cart.decrementarCantidad(item.uid)"
                />

                <!-- Campo editable -->
                <q-input
                  v-model.number="item.cantidad"
                  type="number"
                  outlined
                  dense
                  class="q-mx-sm cantidad-input"
                  style="width: 70px; text-align: center;"
                  min="1"
                  @update:model-value="actualizarCantidad(item)"
                />

                <!-- Botón más -->
                <q-btn
                  flat
                  dense
                  round
                  icon="add"
                  color="positive"
                  @click="cart.incrementarCantidad(item.uid)"
                />
              </div>

              <!-- Subtotal -->
              <div class="col-auto text-right">
                <div class="text-h6 text-black text-bold">
                  ${{ (item.precio * item.cantidad).toLocaleString('es-AR') }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Resumen lateral -->
      <div v-if="cart.carrito.length" class="col-12 col-md-4">
        <div class="resumen-card q-pa-md">

          <div class="row q-col-gutter-sm items-center q-mb-lg">


          </div>

          <div class="resumen q-mb-md">
            <div class="row justify-between">
              <div>Productos ({{ cart.totalItems }})</div>
              <div>${{ cart.totalCarrito.toLocaleString('es-AR') }}</div>
            </div>
          </div>

          <q-separator spaced />

          <div class="row justify-between text-h6 text-bold q-mt-sm q-mb-md">
            <div>Total</div>
            <div>${{ cart.totalCarrito.toLocaleString('es-AR') }}</div>
          </div>

          <q-btn
            label="Finalizar pedido"
            color="positive"
            class="full-width q-mb-sm"
            no-caps
            glossy
            to="/checkout"
          />
          <q-btn
            label="Continuar comprando"
            outline
            color="black"
            class="full-width"
            no-caps
            @click="$router.push('/')"
          />
        </div>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { useCartStore } from 'src/stores/cartStore'

const cart = useCartStore()
const apiUrl = import.meta.env.VITE_API_URL

// Actualiza cantidad manualmente desde el input
function actualizarCantidad(item) {
  if (item.cantidad < 1) item.cantidad = 1

  const index = cart.carrito.findIndex(p => p.uid === item.uid)
  if (index !== -1) {
    cart.carrito[index].cantidad = item.cantidad
    localStorage.setItem('carrito', JSON.stringify(cart.carrito))
  }
}
</script>

<style scoped>
.carrito-page {
  max-width: 1100px;
  margin: 0 auto;
}

/* Card general */
.cart-card {
  background: white;
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

/* Cada ítem */
.cart-item {
  border-bottom: 1px solid #e9e9e9;
}
.cart-item:last-child {
  border-bottom: none;
}

/* Cantidad */
.cantidad-box {
  min-width: 130px;
  justify-content: center;
}
.cantidad-input .q-field__native {
  text-align: center;
  font-weight: 600;
}

/* Resumen */
.resumen-card {
  background: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
  margin-top: 105px;
}

/* Cart vacío */
.empty-cart {
  border: 1px solid #eee;
  border-radius: 12px;
  background: #fff;
  box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
  max-width: 500px;
  margin: 40px auto;
  width: 1000px;
}
.titulo {
  font-weight: bold;
  margin-bottom: 20px;
}
</style>
