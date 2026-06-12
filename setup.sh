#!/bin/bash
set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${GREEN}======================================${NC}"
echo -e "${GREEN}  DYD Accesorios — Setup del Backend  ${NC}"
echo -e "${GREEN}======================================${NC}"

# 1. Crear Laravel en backend/
if [ -d "backend" ] && [ "$(ls -A backend)" ]; then
  echo -e "${YELLOW}⚠️  La carpeta backend/ ya existe. Saltando creación de Laravel.${NC}"
else
  echo -e "${YELLOW}📦 Descargando Laravel en backend/...${NC}"
  mkdir -p backend
  docker run --rm \
    -v "$(pwd)/backend:/app" \
    -w /app \
    composer:2 \
    create-project laravel/laravel . --no-interaction --quiet
  echo -e "${GREEN}✅ Laravel creado${NC}"
fi

# 2. Copiar archivos personalizados encima de Laravel
echo -e "${YELLOW}📋 Aplicando configuración personalizada...${NC}"
cp -r docker/stubs/. backend/
echo -e "${GREEN}✅ Configuración aplicada${NC}"

# 3. Construir e iniciar containers
echo -e "${YELLOW}🐳 Construyendo e iniciando containers...${NC}"
docker compose up -d --build

# 4. Esperar a que PostgreSQL esté listo
echo -e "${YELLOW}⏳ Esperando a PostgreSQL...${NC}"
until docker compose exec postgres pg_isready -U dyd_user -d dydaccesorios > /dev/null 2>&1; do
  sleep 2
done
echo -e "${GREEN}✅ PostgreSQL listo${NC}"

# 5. Publicar config de Sanctum (ya viene incluido en Laravel 11)
echo -e "${YELLOW}📦 Configurando Sanctum...${NC}"
docker compose exec app php artisan vendor:publish \
  --provider="Laravel\Sanctum\SanctumServiceProvider" --quiet 2>/dev/null || true

# 6. Generar clave y correr migraciones
echo -e "${YELLOW}⚙️  Configurando Laravel...${NC}"
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link

# 8. Permisos de storage
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache

echo ""
echo -e "${GREEN}✅ ¡Todo listo!${NC}"
echo ""
echo -e "  API:    ${GREEN}http://localhost:8080/api${NC}"
echo -e "  Admin:  ${GREEN}email: admin@dydaccesorios.com${NC}"
echo -e "          ${GREEN}pass:  admin123${NC}"
echo ""
echo -e "  Para ver los logs: ${YELLOW}docker compose logs -f${NC}"
echo -e "  Para detener:      ${YELLOW}docker compose down${NC}"
