# Práctica 2: Hola mundo con PHP, MySQL y Docker Compose

**Materia:** Electiva 2  
**Estudiante:** Daniel Tavera

## Objetivo
Crear una aplicación web que muestre Hola mundo y se conecte a una base de datos MySQL usando Docker Compose.

## Archivos
- `src/index.php`: código de la aplicación y conexión a MySQL mediante PDO.
- `Dockerfile`: prepara PHP con Apache e instala la extensión `pdo_mysql`.
- `docker-compose.yml`: configura los servicios `app` y `db`, la conexión y el almacenamiento.
- `README.md`: instrucciones de ejecución.

## Requisitos
Docker Desktop iniciado, con el motor funcionando y contenedores Linux. Se necesita internet para descargar las imágenes y construir la aplicación la primera vez.

## Ejecutar en Windows
1. Extraer el ZIP.
2. Entrar a la carpeta que contiene `docker-compose.yml` y `Dockerfile`.
3. En la barra de direcciones del Explorador, escribir `cmd` y presionar Enter.
4. Ejecutar:

```cmd
docker compose up -d --build
```

5. Esperar a que termine la descarga, la construcción y la preparación de MySQL. La primera vez puede tardar varios minutos.
6. Visitar http://localhost:8081.

## Resultado esperado
La página debe mostrar:
- Hola mundo.
- Conexión a MySQL exitosa.
- Base de datos: hola_mundo.
- Fecha y hora devueltas por el servidor MySQL.

La página ejecuta realmente esta consulta:

```sql
SELECT 'Hola mundo' AS mensaje, DATABASE() AS base_datos, CURRENT_TIMESTAMP AS fecha;
```

No requiere crear tablas para esta consulta de demostración. El saludo se obtiene como resultado de MySQL. Si la consulta falla, la página muestra un error en lugar del mensaje de conexión exitosa.

## Cómo funciona
El servicio `app` ejecuta PHP con Apache. El servicio `db` ejecuta MySQL 8.4 y crea automáticamente la base `hola_mundo` y el usuario `usuario_app` al iniciar con un volumen vacío.

La aplicación usa `db` como servidor de base de datos porque es el nombre del servicio en la red de Compose. Dentro del contenedor, `localhost` apuntaría al propio contenedor de PHP.

El puerto 8081 del PC se conecta con el puerto 80 de Apache. MySQL se comunica por la red interna de Docker, sin publicar su puerto en Windows.

El directorio `src` se comparte con `/var/www/html`. El volumen `mysql_data` conserva los datos de MySQL al detener los servicios. Un control de salud ejecuta una consulta antes de iniciar la aplicación.

Las claves `clave_demo_app` y `clave_demo_root` son ejemplos exclusivos de esta práctica local; no son credenciales personales ni de un sistema real.

## Verificar los servicios
Desde la carpeta del proyecto:

```cmd
docker compose ps
```

Deben aparecer `app` activo y `db` activo con estado saludable (`healthy`). Guardar una captura de este resultado y otra del navegador.

## Detener la práctica

```cmd
docker compose down
```

Este comando conserva los datos del volumen. Para volver a iniciar:

```cmd
docker compose up -d
```

## Resolver problemas
- Si el puerto 8081 está ocupado, cambiar `127.0.0.1:8081:80` por `127.0.0.1:8082:80` en Compose, ejecutar otra vez `docker compose up -d --build` y visitar http://localhost:8082.
- Si MySQL tarda en iniciar o aparece un error, revisar:

```cmd
docker compose logs db
docker compose logs app
```

Las variables de creación de MySQL se aplican al inicializar un volumen vacío. Cambiar contraseñas en Compose después de la primera ejecución no cambia automáticamente las claves guardadas en MySQL.

## Publicar en GitHub
Crear un repositorio público con nombre sugerido `electiva2-practica2-php-mysql`. Subir `Dockerfile`, `docker-compose.yml`, `README.md` y la carpeta `src` con `index.php` dentro.

Si se usa la página de GitHub y `index.php` queda en la raíz, editar su nombre a `src/index.php` y confirmar el cambio. No subir únicamente el ZIP.

Comprobar que el repositorio sea público y copiar su URL al foro. La dirección de entrega es la del repositorio de GitHub; `localhost:8081` solo abre la aplicación en el PC que ejecuta Docker.

## Referencias
- https://hub.docker.com/_/php
- https://hub.docker.com/_/mysql
- https://docs.docker.com/compose/how-tos/startup-order/

La ejecución y la conexión deben verificarse en el PC donde se inicien los contenedores.
