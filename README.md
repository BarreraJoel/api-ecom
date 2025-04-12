
## Endpoints

### Autenticación

| Método | Endpoint               | Descripción                      | 
|--------|------------------------|----------------------------------|
| POST  | `/api/auth/login`        | Autentica un usuario       |
| POST  | `/api/auth/register`    | Registra un nuevo usuario        |
| GET   | `/api/auth/current_user`  | Obtiene el usuario logueado |
| GET   | `/api/auth/logout`        | Cierra sesión eliminando los tokens del usuario logueado |


### Productos

| Método | Endpoint               | Descripción                      |
|--------|------------------------|----------------------------------|
| GET    | `/api/products`        | Obtiene todos los productos |
| POST    | `/api/products`    | Agrega un nuevo producto |
| GET   | `/api/products/:id`        |Obtiene un producto por <code>:id</code>|
| PUT   | `/api/products:id`        | Modifica un producto según el <code>:id</code> |
| DELETE   | `/api/products/:id`        | Elimina un producto según el <code>:id</code>  |
| GET   | `/api/products/filter`        | Filtra productos según atributos   |


### Carrito
| Método | Endpoint               | Descripción                      |
|--------|------------------------|----------------------------------|
| GET    | `/api/cart/items`        | Obtiene todos los productos del carrito |
| POST    | `/api/cart/add_item`    | Agrega un ítem al carrito |
| DELETE   | `/api/cart/remove_item`        | Elimina un ítem del carrito |
| GET   | `/api/cart/empty`        | Elimina todos los ítems del carrito |
| GET    | `/api/cart/checkout_mp`        | Genera una preferencia de pago de Mercado Pago en base a los ítems del carrito |
| POST    | `/api/cart/receive_pay`        | Recibe un pago de Mercado Pago luego de que se modifica el <code>status</code> de pago de una preferencia |