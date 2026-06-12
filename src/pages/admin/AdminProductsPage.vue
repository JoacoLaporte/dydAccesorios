<template>
  <q-page padding>

    <!-- Encabezado -->
    <div class="row justify-between items-center q-mb-md">
      <div class="text-h5">Productos</div>
      <q-btn color="grey-9" icon="add" label="Nuevo producto" @click="abrirDialog()" />
    </div>

    <!-- Filtro -->
    <q-select
      v-model="categoriaFiltro"
      :options="categorias"
      label="Filtrar por categoría"
      clearable
      outlined
      dense
      style="max-width: 220px"
      class="q-mb-md"
      @update:model-value="cargarProductos"
    />

    <!-- Tabla -->
    <q-table
      :rows="productos"
      :columns="columnas"
      row-key="id"
      :loading="cargando"
      flat
      bordered
    >
      <template #body-cell-imagen="props">
        <q-td :props="props">
          <q-img
            v-if="props.row.imagen"
            :src="`${apiUrl}/storage/${props.row.imagen}`"
            style="width:56px; height:56px; border-radius:4px"
            fit="cover"
          />
          <q-icon v-else name="image_not_supported" size="36px" color="grey-4" />
        </q-td>
      </template>

      <template #body-cell-activo="props">
        <q-td :props="props">
          <q-badge :color="props.row.activo ? 'positive' : 'grey-5'">
            {{ props.row.activo ? 'Activo' : 'Inactivo' }}
          </q-badge>
        </q-td>
      </template>

      <template #body-cell-acciones="props">
        <q-td :props="props" class="q-gutter-xs">
          <q-btn flat round dense icon="edit" size="sm" @click="abrirDialog(props.row)" />
          <q-btn flat round dense icon="photo_camera" size="sm" color="primary" @click="abrirDialogImagen(props.row)" />
          <q-btn flat round dense icon="delete" size="sm" color="negative" @click="eliminar(props.row.id)" />
        </q-td>
      </template>
    </q-table>

    <!-- Dialog: crear / editar producto -->
    <q-dialog v-model="dialogProducto" persistent>
      <q-card style="min-width: 420px">
        <q-card-section>
          <div class="text-h6">{{ editando ? 'Editar producto' : 'Nuevo producto' }}</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-form @submit.prevent="guardar" class="q-gutter-sm">
            <q-input v-model="form.nombre" label="Nombre" outlined :rules="[v => !!v || 'Requerido']" />
            <div class="row q-col-gutter-sm">
              <div class="col">
                <q-input v-model.number="form.precio" label="Precio" type="number" outlined :rules="[v => v >= 0 || 'Inválido']" />
              </div>
              <div class="col-auto" style="min-width:110px">
                <q-select v-model="form.moneda" :options="['USD','ARS']" label="Moneda" outlined />
              </div>
            </div>
            <q-select v-model="form.categoria" :options="categorias" label="Categoría" outlined :rules="[v => !!v || 'Requerido']" />
            <q-input v-model="form.descripcion" label="Descripción" type="textarea" outlined autogrow />
            <q-toggle v-model="form.activo" label="Producto activo" />
          </q-form>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancelar" v-close-popup />
          <q-btn color="grey-9" label="Guardar" :loading="guardando" @click="guardar" />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <!-- Dialog: subir imagen -->
    <q-dialog v-model="dialogImagen" persistent>
      <q-card style="min-width: 360px">
        <q-card-section>
          <div class="text-h6">Subir imagen</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-file v-model="archivoImagen" label="Seleccionar imagen" outlined accept="image/*" />
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancelar" v-close-popup />
          <q-btn
            color="primary"
            label="Subir"
            :loading="subiendoImagen"
            :disable="!archivoImagen"
            @click="subirImagen"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>

  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from 'src/boot/axios'
import { useQuasar } from 'quasar'

const $q = useQuasar()
const apiUrl = import.meta.env.VITE_API_URL

const productos = ref([])
const cargando = ref(false)
const guardando = ref(false)
const subiendoImagen = ref(false)
const categoriaFiltro = ref(null)
const dialogProducto = ref(false)
const dialogImagen = ref(false)
const editando = ref(false)
const productoSeleccionadoId = ref(null)
const archivoImagen = ref(null)

const categorias = ['accesorios', 'electronica', 'fundas', 'parlantes', 'termos']

const columnas = [
  { name: 'imagen',    label: 'Imagen',    field: 'imagen',    align: 'left' },
  { name: 'nombre',   label: 'Nombre',    field: 'nombre',    sortable: true, align: 'left' },
  { name: 'precio',   label: 'Precio',    field: 'precio',    sortable: true,
    format: (v, row) => `${Number(v).toLocaleString('es-AR')} ${row.moneda}` },
  { name: 'categoria', label: 'Categoría', field: 'categoria', sortable: true },
  { name: 'activo',   label: 'Estado',    field: 'activo' },
  { name: 'acciones', label: 'Acciones',  field: 'acciones',  align: 'center' },
]

const formVacio = () => ({
  nombre: '', precio: 0, moneda: 'USD', categoria: '', descripcion: '', activo: true,
})
const form = ref(formVacio())

async function cargarProductos() {
  cargando.value = true
  try {
    const params = categoriaFiltro.value ? { categoria: categoriaFiltro.value } : {}
    const { data } = await api.get('/admin/products', { params })
    productos.value = data
  } finally {
    cargando.value = false
  }
}

function abrirDialog(producto = null) {
  editando.value = !!producto
  form.value = producto ? { ...producto } : formVacio()
  dialogProducto.value = true
}

function abrirDialogImagen(producto) {
  productoSeleccionadoId.value = producto.id
  archivoImagen.value = null
  dialogImagen.value = true
}

async function guardar() {
  guardando.value = true
  try {
    if (editando.value) {
      await api.put(`/admin/products/${form.value.id}`, form.value)
    } else {
      await api.post('/admin/products', form.value)
    }
    dialogProducto.value = false
    await cargarProductos()
    $q.notify({ type: 'positive', message: 'Producto guardado correctamente' })
  } catch {
    $q.notify({ type: 'negative', message: 'Error al guardar el producto' })
  } finally {
    guardando.value = false
  }
}

async function eliminar(id) {
  $q.dialog({
    title: 'Confirmar eliminación',
    message: '¿Estás seguro de que querés eliminar este producto?',
    cancel: { flat: true, label: 'Cancelar' },
    ok: { color: 'negative', label: 'Eliminar' },
  }).onOk(async () => {
    try {
      await api.delete(`/admin/products/${id}`)
      await cargarProductos()
      $q.notify({ type: 'positive', message: 'Producto eliminado' })
    } catch {
      $q.notify({ type: 'negative', message: 'Error al eliminar el producto' })
    }
  })
}

async function subirImagen() {
  subiendoImagen.value = true
  try {
    const formData = new FormData()
    formData.append('imagen', archivoImagen.value)
    await api.post(`/admin/products/${productoSeleccionadoId.value}/imagen`, formData)
    dialogImagen.value = false
    await cargarProductos()
    $q.notify({ type: 'positive', message: 'Imagen subida correctamente' })
  } catch {
    $q.notify({ type: 'negative', message: 'Error al subir la imagen' })
  } finally {
    subiendoImagen.value = false
  }
}

onMounted(cargarProductos)
</script>
