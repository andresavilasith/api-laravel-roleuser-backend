<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


# Api Laravel 9 de Sistema de roles y permisos
# PHP version 8.0.1
## [Frontend realizado en Angular](https://github.com/andresaviladw/api-angular-roleuser-frontend) 

- Para que funcione correctamente deberias tener instalado PHP 8.0.1 

## Cambiar la version de php del proyecto
- Se puede entrar en el archivo composer.json y escribir su version de php que tiene que ser superior o igual a 7.3.0 y reemplazar a la version 8.0.1
```php
   "require": {
        "php": "^7.3.0",
    },
```


- Basado en el sistema de roles y permisos de [Sistema de roles y permisos en Laravel 8](https://github.com/andresaviladw/role_user)

### Lo que contiene:

- [x] TDD(Feature test driven development)
- [x] Subida y visualización de imagenes
- [x] Validación de datos mediante Request en formularios
- [x] Autenticación con Laravel Passport

## Instalacion  
1. Instalar [Wamp(Solo Windows)](https://www.wampserver.com/en/) , [Xampp](https://www.apachefriends.org/es/index.html) u otro según  su preferencia 
2. Instalar composer [Descargar composer](https://getcomposer.org/download/)
3. Clonar el repositorio en el directorio de tu eleccion
```
git clone https://github.com/andresaviladw/api-laravel-roleuser-backend.git
```
4. Instalar composer  
```js
composer install 
```

5. Cambiar el nombre del archivo **.env.example** _(Si esta como **env.example**)_ a **.env**


6. Crear una base de datos en phpMyAdmin y configurar el archivo .env 

```php
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1   
   DB_PORT=3306
   DB_DATABASE=Nombre de Base De Datos Creada En phpMyAdmin
   DB_USERNAME=Nombre de Usuario en phpMyAdmin
   DB_PASSWORD=Contraseña en phpMyAdmin
```
   


#### En mi caso es:
```php
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306    
   DB_DATABASE=medio 
   DB_USERNAME=root    
   DB_PASSWORD=
```

   
7. Generar una nueva llave de laravel con el comando:
```php
php artisan key:generate
```
8. Ejecutar migraciones con el siguiente comando: 
```php
php artisan migrate --seed
```
9. Instalar claves de acceso
```php
php artisan passport:install
```
10. Aparecera algo como lo siguiente en la consola

- Client ID: 1
* Client secret: `dCu1eD7c8JVPp0Upk8tBgTbCU0X9beeqp60JpUw1`
- Client ID: 2
* Client secret: `CnmUKWB7A6l0JeKVZIOrOYwWb4e7FFUEYkJjdkj0`

11. Copiar las claves de acceso en algun archivo de texto o acceder a la tabla `oauth_clients` en la base de datos donde2 estan alojadas

12. Escribir la clave de acceso del Client ID: 2 donde sea el inicio de sesion en el frontend, en este caso la clave de acceso seria: `CnmUKWB7A6l0JeKVZIOrOYwWb4e7FFUEYkJjdkj0`

13. En mi caso seria en el repositorio: [Frontend Angular](https://github.com/andresaviladw/api-angular-roleuser-frontend)

14. Entrar a `src/app/services/` abrir el archivo `global.service.ts` y pegar la clave de accesso en:

```js
export var global={
    clientSecret:'Escribir clave de acceso'
}
```


- Quedaria asi 

```js
export var global={
    clientSecret:'CnmUKWB7A6l0JeKVZIOrOYwWb4e7FFUEYkJjdkj0'
}
```


15. Ejecutar el proyecto: 
```
php artisan serve
```

16. Comandos para verificacion de test
	```php
	php artisan test
	```
	
	```php
	php vendor/phpunit/phpunit/phpunit
	```
