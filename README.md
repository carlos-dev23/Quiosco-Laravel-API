<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


<h1 align="center">Quiosco — API</h1>

<p align="center">
  API REST para un quiosco de comida, hecha con Laravel 13 y Sanctum.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/Sanctum-4-FF2D20" alt="Sanctum 4">
  <img src="https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white" alt="MySQL">
</p>

---

API REST para un quiosco de comida: catálogo de categorías y productos, pedidos de clientes y gestión de pedidos por un administrador. Backend del proyecto [Quiosco-React-FrontEnd](https://github.com/carlos-dev23/Quiosco-React-FrontEnd).

## Funcionalidades

- Autenticación con **Laravel Sanctum** (tokens Bearer): registro, login y logout.
- Listado de categorías y productos (con API Resources).
- Creación de pedidos con sus productos y cantidades (tabla pivote).
- Listado de pedidos pendientes y marcado como completado.
- Marcar un producto como no disponible.
- Usuarios con bandera `admin`.

## Stack

PHP 8.3 · Laravel 13 · Laravel Sanctum 4 · MySQL · Eloquent · Form Requests · API Resources

## Endpoints

| Método | Ruta | Auth | Descripción |
|---|---|---|---|
| POST | `/api/registro` | No | Crea usuario y devuelve token |
| POST | `/api/login` | No | Devuelve token y usuario |
| POST | `/api/logout` | Sanctum | Revoca el token actual |
| GET | `/api/user` | Sanctum | Usuario autenticado |
| GET | `/api/categorias` | No | Lista de categorías |
| GET | `/api/productos` | No | Lista de productos |
| PUT | `/api/productos/{id}` | No* | Marca el producto como no disponible |
| GET | `/api/pedidos` | Sanctum | Pedidos pendientes con usuario y productos |
| POST | `/api/pedidos` | Sanctum | Crea un pedido con sus productos |
| PUT | `/api/pedidos/{id}` | Sanctum | Marca el pedido como completado |

\* Ver "Siguientes pasos".

## Modelo de datos

```mermaid
erDiagram
    USERS ||--o{ PEDIDOS : realiza
    CATEGORIAS ||--o{ PRODUCTOS : agrupa
    PEDIDOS ||--o{ PEDIDO_PRODUCTOS : contiene
    PRODUCTOS ||--o{ PEDIDO_PRODUCTOS : aparece_en
    USERS { bigint id PK
            string name
            string email
            boolean admin }
    CATEGORIAS { bigint id PK
                 string nombre
                 string icono }
    PRODUCTOS { bigint id PK
                string nombre
                double precio
                string imagen
                boolean disponible
                bigint categoria_id FK }
    PEDIDOS { bigint id PK
              bigint user_id FK
              double total
              boolean estado }
    PEDIDO_PRODUCTOS { bigint id PK
                       bigint pedido_id FK
                       bigint producto_id FK
                       int cantidad }
```

## Flujo de un pedido

```mermaid
sequenceDiagram
    participant C as Cliente (React)
    participant A as API Laravel
    participant D as MySQL
    C->>A: POST /api/pedidos (Bearer token, total, productos[])
    A->>D: INSERT pedidos
    A->>D: INSERT pedido_productos (lote)
    A-->>C: "Pedido guardado correctamente"
    Note over A,D: El admin consulta GET /api/pedidos (estado = 0)
    A->>D: PUT /api/pedidos/{id} → estado = 1
```

## Instalación

```bash
git clone https://github.com/carlos-dev23/Quiosco-Laravel-API.git
cd Quiosco-Laravel-API
composer install
cp .env.example .env
php artisan key:generate
# configura DB_* en .env (por defecto: base backend_quiosco)
php artisan migrate --seed   # carga categorías y productos de ejemplo
php artisan serve
```

## Siguientes pasos

- Proteger las rutas de administrador (actualizar productos / pedidos) con un middleware o Policy basado en `users.admin`.
- Validar el pedido con un Form Request y envolver la creación en una transacción.
- Pruebas Feature con Pest (auth, creación de pedido) y CI con GitHub Actions.
- Restringir `allowed_origins` de CORS al dominio del frontend en producción.del frontend en producción.urced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
