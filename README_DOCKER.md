# Contacts API - Docker Setup

## Requisitos

- Docker Desktop instalado y en ejecución
- Docker Compose (incluido en Docker Desktop)

## Instalación

1. Clonar el repositorio:
   ```bash
   git clone git@github.com:dfruizc/contacts-api.git
   cd contacts-api
   ```

2. Construir y levantar los contenedores:
   ```bash
   docker-compose up -d --build
   ```

3. Verificar que los contenedores estén en ejecución:
   ```bash
   docker-compose ps
   ```

4. Probar la API:
   ```bash
   curl http://localhost:8080/contacts
   ```

## Endpoints

La API estará disponible en `http://localhost:8080`

- `GET    /contacts` - Listar todos los contactos
- `GET    /contacts/{id}` - Obtener un contacto
- `POST   /contacts` - Crear contacto
- `DELETE /contacts/{id}` - Eliminar contacto

### Ejemplo POST

```bash
curl -X POST http://localhost:8080/contacts \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Ana",
    "last_name": "García",
    "email": "ana@example.com",
    "phones": ["3110000000", "+57 1 2223333"]
  }'
```

## Comandos útiles

```bash
# Ver logs
docker-compose logs -f

# Detener contenedores
docker-compose down

# Detener y eliminar datos
docker-compose down -v

# Reconstruir contenedores
docker-compose up -d --build

# Acceder a MySQL
docker-compose exec db mysql -u root -prootpassword contacts_api

# Acceder al contenedor web
docker-compose exec web bash
```

## Puertos

- **Web**: `http://localhost:8080`
- **MySQL**: `localhost:3307`

## Desarrollo

Los archivos del proyecto están montados como volúmenes, por lo que los cambios en el código se reflejan inmediatamente sin necesidad de reconstruir los contenedores.
