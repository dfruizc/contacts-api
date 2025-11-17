# Contacts API

API REST desarrollada en PHP puro para gestionar contactos con sus números de teléfono. Implementa arquitectura en capas, validaciones de datos y soporte para múltiples teléfonos por contacto.

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

La API estará disponible en `http://localhost:8080`

## Endpoints

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/contacts` | Lista todos los contactos |
| GET | `/contacts/{id}` | Obtiene un contacto específico |
| POST | `/contacts` | Crea un nuevo contacto |
| DELETE | `/contacts/{id}` | Elimina un contacto |

## Ejemplo de uso

### Crear un contacto

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

### Listar contactos

```bash
curl http://localhost:8080/contacts
```

### Obtener un contacto

```bash
curl http://localhost:8080/contacts/1
```

### Eliminar un contacto

```bash
curl -X DELETE http://localhost:8080/contacts/1
```

## Estructura del proyecto

```
contacts-api/
├── config/              # Configuración
│   └── database.php    # Conexión a base de datos
├── controllers/         # Controladores
│   └── ContactController.php
├── models/              # Modelos
│   └── Contact.php
├── public/              # Punto de entrada
│   └── index.php       # Router
├── docker/              # Configuración Docker
│   ├── apache-config.conf
│   └── init.sql
├── docker-compose.yml   # Orquestación de servicios
└── Dockerfile          # Imagen PHP/Apache
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
```

## Características

- API REST sin framework
- Arquitectura en capas (separación de responsabilidades)
- Validaciones de datos
- Múltiples teléfonos por contacto
- Dockerizado para fácil despliegue
- Base de datos inicializada automáticamente

## Testing

El proyecto incluye:
- `api-requests.http` - Peticiones para REST Client (VS Code)
- `Contacts-API.postman_collection.json` - Colección de Postman

## Configuración

El archivo `.env` (opcional) permite personalizar la configuración:

```env
DB_HOST=db
DB_NAME=contacts_api
DB_USER=root
DB_PASSWORD=rootpassword
```

Si no existe `.env`, se utilizan valores por defecto que funcionan correctamente.

## Puertos

- **API Web**: `http://localhost:8080`
- **MySQL**: `localhost:3307`

## Repositorio

- **GitHub**: [https://github.com/dfruizc/contacts-api](https://github.com/dfruizc/contacts-api)
- **SSH**: `git@github.com:dfruizc/contacts-api.git`



