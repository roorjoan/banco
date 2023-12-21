# Instalación del Proyecto Banco

Este documento proporciona instrucciones paso a paso para instalar un proyecto Laravel. Además, se ha generado la documentación para la API utilizando Swagger.

## Requisitos Previos

Asegúrate de tener instalados los siguientes requisitos en tu sistema:

-   PHP (versión 8.1 o superior)
-   Composer
-   MySQL o cualquier otro sistema de gestión de bases de datos compatible con Laravel

## Pasos de Instalación

1. **Clonar el Repositorio:**

    ```bash
    git clone https://github.com/roorjoan/banco.git
    ```

2. **Instalar Dependencias:**

    ```bash
    cd banco
    composer install
    ```

3. **Copiar el Archivo de Configuración:**

    ```bash
    cp .env.example .env
    ```

Luego, edita el archivo .env con los detalles de tu base de datos y otras configuraciones necesarias.

4. **Generar Clave de Encriptación:**

    ```bash
    php artisan key:generate
    ```

5. **Migrar la Base de Datos:**

    ```bash
    php artisan migrate
    ```

6. **Iniciar el Servidor de Desarrollo:**

    ```bash
    php artisan serve
    ```

## Acceder a la Documentación de la API

La documentación de la API se ha generado utilizando Swagger. Puedes acceder a la documentación en http://localhost:8000/docs después de iniciar el servidor de desarrollo.
