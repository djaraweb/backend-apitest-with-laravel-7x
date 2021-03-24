# APIREST para realizar procesos de Testing con Laravel

##### Descripción:

Esta es una aplicación backend que implementa methodos para realizar Testing. Que permita consumir cualquier aplicacion Frontend.

Ademas se tienen las rutas protegidas integrando la seguridad de Laravel Passport, por tal en las cabeceras (headers) se debe agregar el accessToken generado en el login.

Se debe configurar la carpeta publica en un nivel superior de la raiz de este proyecto con el nombre de **_public_html_**

##### Servicios de la aplicación:

###### Usuarios: (Users)

-   **_POST_** => /api/auth/register [Registra una Usuario-Ruta Protegida]
-   **_POST_** => /api/login [Realiza el Login de un usuario para generarl el Token]
-   **_GET_** => /api/auth/user [Obtiene el usuario Logueado a partir del Token].
-   **_POST_** => /api/auth/logout [Permite Cerrar Sessión de un usuario logueado a partir del token]

###### Directorio Teléfonico: (Contactos)

-   **_POST_** => /api/directorios [Registra un directorio telefonico]
-   **_PUT_** => /api/directorios/:id [Actualiza un directorio teléfonico]
-   **_GET_** => /api/directorios [Obtiene información de los directorios telefonicos registrados].
-   **_GET_** => /api/directorios?txtbuscar [Permite filtrar por nombre y nro de telefono]
-   **_DELETE_** => /api/directorios/:id [Permite eliminar un directorio telefonico]

###### Tareas: (Tasks)

-   **_POST_** => /api/tasks [Registra una Tarea]
-   **_PUT_** => /api/tasks/:id [Actualiza una Tarea]
-   **_GET_** => /api/tasks [Obtiene información de las Tareas registrados].
-   **_DELETE_** => /api/tasks/:id [Permite eliminar una Tarea]

### Técnologia empleada

-   [Laravel]

## Pruebas realizadas con Postman y se integraron TestUnit con la libreria PHPUnit incorporada en el framework.

##### _**Ejecutar los siguientes comandos según sea el caso**_

```sh
$ php artisan test
$ vendor/bin/phpunit

$ vendor/bin/phpunit --filter=LoginTest
$ vendor/bin/phpunit --filter=TasksTest

$ php artisan test --filter=LoginTest
$ php artisan test --filter=TasksTest
```

PASS Tests\Feature\LoginTest

-   ✓ register user when fields is not present
-   ✓ register user when field name is not present
-   ✓ register user when field email is not valid
-   ✓ register user when fields is valid
-   ✓ login when fields is not present
-   ✓ login when user not exists
-   ✓ login when user exists

PASS Tests\Feature\TasksTest

-   ✓ list tasks when no records exist
-   ✓ list tasks when records exist
-   ✓ list tasks when filter no records exist
-   ✓ list tasks when filter records exist
-   ✓ create task when fields is not present
-   ✓ create task when field title is not present
-   ✓ create task when field completed is not present
-   ✓ create task when is present
-   ✓ update task when field not exist
-   ✓ update task when fields is not present
-   ✓ update task when field title is not present
-   ✓ update task when field completed is not present
-   ✓ update task when fields is present
-   ✓ destroy task when field id is not exist
-   ✓ destroy task when field id is present

**Free Software**

[//]: #
[laravel]: https://laravel.com/docs/7.x
