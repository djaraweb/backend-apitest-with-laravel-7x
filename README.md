# APIREST para realizar procesos de Testing con Laravel

##### Descripción:

Esta es una aplicación backend que implementa methodos para realizar Testing. Que permita consumir cualquier aplicacion Frontend.

Se debe configurar la carpeta publica en un nivel superior de la raiz de este proyecto con el nombre de **_public_html_**

##### Servicios de la aplicación:

###### Usuarios: (Users)

- **_POST_** => /api/auth/register [Registra una Usuario-Ruta Protegida]
- **_POST_** => /api/login [Realiza el Login de un usuario para generarl el Token]
- **_GET_** => /api/auth/user [Obtiene el usuario Logueado a partir del Token].
- **_POST_** => /api/auth/logout [Permite Cerrar Sessión de un usuario logueado a partir del token]


###### Directorio Teléfonico: (Contactos)

- **_POST_** => /api/directorios [Registra un directorio telefonico]
- **_PUT_** => /api/directorios/:id [Actualiza un directorio teléfonico]
- **_GET_** => /api/directorios [Obtiene información de los directorios telefonicos registrados].
- **_GET_** => /api/directorios?txtbuscar [Permite filtrar por nombre y nro de telefono]
- **_DELETE_** => /api/directorios/:id [Permite eliminar un directorio telefonico]

###### Tareas: (Tasks)

- **_POST_** => /api/tasks [Registra una Tarea]
- **_PUT_** => /api/tasks/:id [Actualiza una Tarea]
- **_GET_** => /api/tasks [Obtiene información de las Tareas registrados].
- **_DELETE_** => /api/tasks/:id [Permite eliminar una Tarea]


### Técnologia empleada

- [Laravel]

## Pruebas realizadas con Postman

**Free Software**

[//]: #
[Laravel]: https://laravel.com/docs/7.x
