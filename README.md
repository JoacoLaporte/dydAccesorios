# DYD Accesorios

E-commerce de accesorios para celulares. Permite a los clientes explorar productos por categoría, armar un carrito y realizar pedidos. Incluye un panel de administración para gestión de productos.

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Frontend | Vue 3 + Quasar Framework |
| Backend | Laravel 11 (API REST) |
| Base de datos | PostgreSQL |
| Infraestructura | Docker + Docker Compose |
| Autenticación | Laravel Sanctum (tokens) |

## Funcionalidades

**Tienda**
- Catálogo de productos por categoría (accesorios, fundas, parlantes, termos, electrónica)
- Precios en USD convertidos automáticamente a pesos usando la cotización del dólar blue
- Carrito persistente (localStorage)
- Checkout con datos de envío o retiro en sucursal
- Confirmación de pedido por email (cliente y tienda)

**Panel admin** (`/admin`)
- Login con autenticación por token
- ABM de productos (crear, editar, eliminar)
- Subida de imágenes por producto
- Filtro por categoría

## Requisitos

- [Docker Desktop](https://www.docker.com/products/docker-desktop)
- Node.js 20+

## Instalación y uso local

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/dydaccesorios.git
cd dydaccesorios
```

### 2. Levantar el backend (primera vez)

```bash
./setup.sh
```

Este script descarga Laravel, configura la base de datos, corre las migraciones y levanta todos los containers de Docker automáticamente.

### 3. Levantar el frontend

```bash
npm install
npm run dev
```

### URLs

| Servicio | URL |
|---|---|
| Tienda | http://localhost:9100 |
| Panel admin | http://localhost:9100/#/admin/login |
| API | http://localhost:8080/api |

### Credenciales del admin (desarrollo)

```
Email:      admin@dydaccesorios.com
Contraseña: admin123
```

### Comandos útiles

```bash
# Levantar backend
docker compose up -d

# Detener backend
docker compose down

# Ver logs del backend
docker compose logs -f

# Acceder al contenedor de Laravel
docker compose exec app bash
```

## Estructura del proyecto

```
dydaccesorios/
├── src/                  # Frontend Vue/Quasar
│   ├── pages/            # Páginas (catálogo, carrito, checkout, admin)
│   ├── components/       # Componentes reutilizables
│   ├── stores/           # Estado global (Pinia)
│   └── layouts/          # Layouts principal y admin
├── backend/              # API Laravel
│   ├── app/
│   │   ├── Http/Controllers/Api/   # ProductController, OrderController, AuthController
│   │   ├── Mail/                   # Clases de email
│   │   └── Models/                 # Product, User
│   ├── database/migrations/        # Migraciones
│   └── routes/api.php              # Definición de endpoints
├── docker/               # Configuración Docker
└── docker-compose.yml
```

## Variables de entorno

Copiá `backend/.env.example` a `backend/.env` y completá con tus credenciales SMTP reales para producción.
