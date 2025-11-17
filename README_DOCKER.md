# Contacts API - Docker Setup

## Requisitos

- Docker Desktop instalado y en ejecución
- Docker Compose (incluido en Docker Desktop)

## Instalación rápida

1. Clonar o navegar al directorio del proyecto

2. Crear el archivo `.env` (opcional, los valores por defecto funcionan):
   ```bash
   cp .env.example .env
   ```
   Editar `.env` si se necesita cambiar la configuración de la base de datos.

3. Construir y levantar los contenedores:
   ```bash
   docker-compose up -d --build
   ```

4. Verificar que los contenedores estén en ejecución:
   ```bash
   docker-compose ps
   ```

5. Probar la API:
   - Abrir en el navegador: `http://localhost:8080/contacts`
   - La respuesta esperada es `[]` en formato JSON (lista vacía)

## Endpoints

La API estará disponible en: `http://localhost:8080`

- `GET    http://localhost:8080/contacts` - Listar todos los contactos
- `GET    http://localhost:8080/contacts/{id}` - Obtener un contacto
- `POST   http://localhost:8080/contacts` - Crear contacto
- `DELETE http://localhost:8080/contacts/{id}` - Eliminar contacto

### Ejemplo POST (usando Postman o curl):

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

### Ver logs
```bash
docker-compose logs -f
```

### Ver logs solo del web server
```bash
docker-compose logs -f web
```

### Ver logs solo de la base de datos
```bash
docker-compose logs -f db
```

### Detener los contenedores
```bash
docker-compose down
```

### Detener y eliminar volúmenes (borra la base de datos)
```bash
docker-compose down -v
```

### Reconstruir los contenedores
```bash
docker-compose up -d --build
```

### Acceder a la base de datos MySQL
```bash
docker-compose exec db mysql -u root -prootpassword contacts_api
```

### Acceder al contenedor web
```bash
docker-compose exec web bash
```

## Estructura de puertos

- **Web (PHP/Apache)**: `localhost:8080`
- **MySQL**: `localhost:3307`

## Solución de problemas

### Error: "Port already in use"
Si el puerto 8080 está ocupado, cambiar el puerto en `docker-compose.yml`:
```yaml
ports:
  - "8081:80"  # Cambiar 8080 por 8081
```

### Error de conexión a la base de datos
1. Verificar que el contenedor `db` esté en ejecución: `docker-compose ps`
2. Esperar unos segundos después de `docker-compose up` para que MySQL se inicialice
3. Verificar los logs: `docker-compose logs db`

### La base de datos no se crea
El script `docker/init.sql` se ejecuta automáticamente al crear el contenedor por primera vez. Para recrear la base de datos:
```bash
docker-compose down -v
docker-compose up -d
```

## Desarrollo

Los archivos del proyecto están montados como volúmenes, así que los cambios en el código se reflejan inmediatamente sin necesidad de reconstruir los contenedores.

Solo es necesario reconstruir si:
- Cambia `Dockerfile`
- Cambian dependencias del sistema
- Cambia `docker-compose.yml`


