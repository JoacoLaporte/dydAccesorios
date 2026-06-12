<template>
    <q-page class="q-pa-md checkout-page">
      <h4 class="titulo">Finalizar la compra</h4>
  
      <q-form @submit.prevent="enviarPedido" class="q-gutter-md">
  
        <!-- DATOS PERSONALES -->
        <div class="section-card q-pa-md">
          <h6 class="section-title">Datos personales</h6>
  
          <div class="row q-col-gutter-md q-mt-sm">
            <div class="col-12 col-md-6">
              <q-input
                v-model="form.nombre"
                label="Nombre"
                outlined
                dense
                :rules="[val => !!val || 'Campo obligatorio']"
              />
            </div>
  
            <div class="col-12 col-md-6">
              <q-input
                v-model="form.apellido"
                label="Apellido"
                outlined
                dense
                :rules="[val => !!val || 'Campo obligatorio']"
              />
            </div>
  
            <div class="col-12 col-md-6">
                <q-input
                v-model="form.email"
                label="Correo electrónico"
                type="email"
                outlined
                dense
                :rules="[
                    val => !!val || 'Campo obligatorio',
                    val => /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/.test(val) || 'Correo inválido'
                ]"
                />
            </div>
  
            <div class="col-12 col-md-6">
              <q-input
                v-model="form.celular"
                label="Teléfono / Móvil"
                type="tel"
                outlined
                dense
                mask="##########"
                :rules="[val => !!val || 'Campo obligatorio']"
              />
            </div>
          </div>
        </div>
  
        <!-- ENTREGA -->
        <div class="section-card q-pa-md">
          <h6 class="section-title">Método de entrega</h6>
  
          <q-option-group
            v-model="form.metodoEntrega"
            :options="[
              { label: 'Retiro en sucursal', value: 'Retiro sucursal' },
              { label: 'Envío a domicilio', value: 'Envio' }
            ]"
            color="green"
            inline
            class="q-mt-sm"
          />
  
          <!-- Si elige RETIRO -->
          <div v-if="form.metodoEntrega === 'Retiro sucursal'" class="q-mt-md q-animate--fade">
            <q-select
              v-model="form.sucursal"
              label="Seleccioná la sucursal"
              outlined
              dense
              :options="sucursales"
              :rules="[val => !!val || 'Debes elegir una sucursal']"
            />
          </div>
  
          <!-- Si elige ENVÍO -->
          <div v-if="form.metodoEntrega === 'Envio'" class="q-mt-md q-animate--fade">
            <div class="row q-col-gutter-md">
              <div class="col-12 col-md-8">
                <q-input
                  v-model="form.calle"
                  label="Calle y número"
                  outlined
                  dense
                  :rules="[val => !!val || 'Campo obligatorio']"
                />
              </div>
  
              <div class="col-12 col-md-4">
                <q-input
                  v-model="form.codigoPostal"
                  label="Código postal"
                  outlined
                  dense
                  :rules="[val => !!val || 'Campo obligatorio']"
                />
              </div>
  
              <div class="col-12 col-md-6">
                <q-input
                  v-model="form.localidad"
                  label="Localidad"
                  outlined
                  dense
                  :rules="[val => !!val || 'Campo obligatorio']"
                />
              </div>
  
              <div class="col-12 col-md-6">
                <q-input
                  v-model="form.provincia"
                  label="Provincia"
                  outlined
                  dense
                  :rules="[val => !!val || 'Campo obligatorio']"
                />
              </div>
            </div>
          </div>
        </div>
  
        <!-- BOTONES -->
        <div class="q-mt-lg text-right">
          <q-btn
            label="Volver al carrito"
            flat
            color="black"
            class="q-mr-sm"
            @click="$router.push('/cart')"
          />
          <q-btn
            label="Confirmar pedido"
            color="green"
            glossy
            no-caps
            type="submit"
            @click="confirmarPedido()"
          />
        </div>
      </q-form>

      <!-- Cuadro de confirmación -->
    <q-dialog v-model="pedidoExitoso" persistent>
      <q-card class="q-pa-lg text-center" style="max-width: 400px">
        <q-card-section>
          <h5 class="text-green">¡Realizaste tu pedido exitosamente!</h5>
          <p>Te enviamos un correo con la confirmación y tu número de pedido.</p>
        </q-card-section>
        <q-card-actions align="center">
          <q-btn color="green" label="Aceptar" @click="pedidoExitoso = false ; $router.push('/')" />
        </q-card-actions>
      </q-card>
    </q-dialog>
    </q-page>
  </template>
  
  <script setup>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { api } from 'src/boot/axios'
import { useCartStore } from 'src/stores/cartStore'

const $q = useQuasar()
const cartStore = useCartStore()
const pedidoExitoso = ref(false) // estado del modal
const sucursales = [
  'Larrea 193 - CABA'
]

// Modelo del formulario
const form = ref({
  nombre: '',
  apellido: '',
  email: '',
  celular: '',
  metodoEntrega: 'Retiro Sucursal',
  sucursal: '',
  calle: '',
  codigoPostal: '',
  localidad: '',
  provincia: ''
})

// Función que se ejecuta al hacer click en “Confirmar pedido”
async function enviarPedido() {
  if (!form.value.nombre || !form.value.email) {
    $q.notify({ color: 'red', message: 'Completá los campos obligatorios' })
    return
  }

  try {
    const payload = {
      ...form.value,
      carrito: cartStore.carrito,
      total: cartStore.totalCarrito,
    }

    const { data } = await api.post('/orders', payload)

    if (data.status === 'ok') {
      cartStore.vaciarCarrito()
      pedidoExitoso.value = true
    }
  } catch (err) {
    console.error(err)
    $q.notify({ color: 'red', message: 'Error al enviar el pedido. Intentá de nuevo.' })
  }
  // pedidoExitoso.value = true
  
}

async function confirmarPedido() {
  await enviarPedido()

  if (pedidoExitoso.value) {
    window.open(
      "https://wa.me/5491150016367?text=Hola!%20Quisiera%20hacer%20una%20consulta%20sobre%20un%20producto",
      "_blank"
    )
  }
}
</script>
  
  <style scoped>
  .checkout-page {
    max-width: 850px;
    margin: 0 auto;
  }
  
  .section-card {
    background: #fafafa;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
  }
  
  .section-title {
    color: #000000;
    font-weight: 700;
    font-size: 17px;
  }
  
  .titulo {
    font-weight: bold;
    margin-bottom: 25px;
    color: black;
  }
  </style>
  