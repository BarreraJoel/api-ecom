
## Endpoints

### Autenticación

| Método | Endpoint               | Descripción                      |  Autenticado |
|--------|------------------------|----------------------------------|--------------|
| POST  | `/api/auth/login`        | Autentica un usuario       |  :x: |
| POST  | `/api/auth/register`    | Registra un nuevo usuario        | :x: |
| GET   | `/api/auth/user`  | Obtiene el usuario logueado |   :white_check_mark: |
| GET   | `/api/auth/logout`        | Cierra sesión eliminando los tokens del usuario logueado | :white_check_mark:   |


### Productos

| Método | Endpoint               | Descripción                      | Autenticado |
|--------|------------------------|----------------------------------|-------------|
| GET    | `/api/products`           | Obtiene todos los productos |   :white_check_mark: |
| GET   | `/api/products/:id`        |Obtiene un producto por `:id`|   :white_check_mark: |
| POST    | `/api/products`          | Agrega un nuevo producto |   :white_check_mark: |
| PUT   | `/api/products/:id`        | Modifica un producto según el `:id` |   :white_check_mark: |
| DELETE   | `/api/products/:id`     | Elimina un producto según el `:id`  |   :white_check_mark: |
| GET   | `/api/products/filter`     | Filtra productos según atributos   |   :white_check_mark: |


### Carrito
| Método | Endpoint               | Descripción                      |  Autenticado |
|--------|------------------------|----------------------------------|--------------|
| GET    | `/api/cart/items`          | Obtiene todos los ítems del carrito |   :white_check_mark: |
| POST    | `/api/cart/items`         | Agrega un ítem al carrito |   :white_check_mark: |
| PUT    | `/api/cart/items/:id`      | Actualiza la cantidad de un ítem del carrito |   :white_check_mark: |
| DELETE   | `/api/cart/items`        | Elimina todos los ítems del carrito |   :white_check_mark: |
| DELETE   | `/api/cart/items/:id`    | Elimina un ítem del carrito según el `:id` |   :white_check_mark: |

### Checkout
| Método | Endpoint               | Descripción                      | Autenticado |
|--------|------------------------|----------------------------------|-------------|
| GET    | `/api/checkout/mercado_pago` | Genera una preferencia de pago de Mercado Pago en base a los ítems del carrito |   :white_check_mark: |
| POST    | `/api/checkout/receive_pay` | Recibe un pago de Mercado Pago luego de que se modifica el `status` de pago de una preferencia |   :white_check_mark: |


### Categorias

| Método | Endpoint               | Descripción                      | Autenticado |
|--------|------------------------|----------------------------------|-------------|
| GET    | `/api/categories`           | Obtiene todos las categorias |   :white_check_mark: |
| GET   | `/api/categories/:id`        |Obtiene una categoria por `:id`|   :white_check_mark: |
| POST    | `/api/categories`          | Agrega una nueva categoria |   :white_check_mark: |
| PUT   | `/api/categories/:id`        | Modifica una categoria según el `:id` |   :white_check_mark: |
| DELETE   | `/api/categories/:id`     | Elimina una categoria según el `:id`  |   :white_check_mark: |

### Usuarios

| Método | Endpoint               | Descripción                      | Autenticado |
|--------|------------------------|----------------------------------|-------------|
| GET    | `/api/users`           | Obtiene todos los usuarios |   :white_check_mark: |
| GET   | `/api/users/:id`        |Obtiene un usuario por `:id`|   :white_check_mark: |
| PUT   | `/api/users/:id`        | Modifica un usuario según el `:id` |   :white_check_mark: |
| DELETE   | `/api/users/:id`     | Elimina un usuario según el `:id`  |   :white_check_mark: |

### Ordenes

| Método | Endpoint               | Descripción                      | Autenticado |
|--------|------------------------|----------------------------------|-------------|
| GET    | `/api/orders`           | Obtiene todos las ordenes |   :white_check_mark: |

### Roles

| Método | Endpoint               | Descripción                      | Autenticado |
|--------|------------------------|----------------------------------|-------------|
| GET    | `/api/roles`           | Obtiene todos los roles |   :white_check_mark: |
| GET   | `/api/roles/:id`        |Obtiene un rol por `:id`|   :white_check_mark: |
| POST    | `/api/roles`          | Agrega un nuevo rol |   :white_check_mark: |
| PUT   | `/api/roles/:id`        | Modifica un rol según el `:id` |   :white_check_mark: |
| DELETE   | `/api/roles/:id`     | Elimina un rol según el `:id`  |   :white_check_mark: |
