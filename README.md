# PracticaProf_1

# PracticaProf_1
estado: casi terminado

## Descripción

PracticaProf_1 es una aplicación quepermite gestionar el despliegue de maquinarias de construcción a nivel nacional. perimite llevar registro de las obras asi como los mantenimientos de las mismas y un cronograma de trabajos. Este proyecto está diseñado para empresas de construccion.

## Requisitos

Para ejecutar este proyecto, asegúrate de cumplir con los siguientes requisitos:

- **PHP**: 8.1 o superior (compatible con Laravel 11).
- **Composer**: 2.2 o superior.
- **Node.js y NPM** (opcional, para herramientas de frontend como Laravel Mix o Vite).
- **Base de datos**: MySQL 5.7+, PostgreSQL, SQLite, u otra compatible con Laravel.
- **Extensiones de PHP**:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
- **Herd**: Última versión compatible con Laravel (disponible para macOS y Windows).
- **Sistema operativo**: Windows, macOS o Linux (Herd es compatible con macOS y Windows; para Linux, considera Docker o LAMP/LEMP).

## Instalación

Sigue estos pasos para configurar y ejecutar el proyecto:

1. **Instalar Herd**:
   - Descarga e instala Herd desde [https://herd.laravel.com/](https://herd.laravel.com/).
   - Configura Herd para gestionar PHP y Composer (asegúrate de usar PHP 8.1+).
   - Opcionalmente, utiliza el servidor Nginx de Herd o el servidor de desarrollo de Laravel.

2. **Instalar Laravel Installer**:
   - Ejecuta en la terminal:
     ```bash
     composer global require laravel/installer:5.14.0
     ```
   - Asegúrate de que el directorio de Composer esté en tu PATH para usar el comando `laravel`.

3. **Clonar o crear el proyecto**:
   - Para un nuevo proyecto:
     ```bash
     laravel new nombre-del-proyecto
     ```
   - Para un proyecto existente:
     ```bash
     git clone <url-del-repositorio>
     cd nombre-del-proyecto
     ```

4. **Instalar dependencias**:
   - Dentro del directorio del proyecto:
     ```bash
     composer install
     ```
   - Si usas frontend:
     ```bash
     npm install
     npm run dev
     ```

5. **Configurar el entorno**:
   - Copia el archivo de entorno:
     ```bash
     cp .env.example .env
     ```
   - Edita `.env` para configurar la base de datos (por ejemplo, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
   - Genera la clave de la aplicación:
     ```bash
     php artisan key:generate
     ```

6. **Ejecutar migraciones y seeders** (si aplica):
   - Crea las tablas en la base de datos:
     ```bash
     php artisan migrate
     ```
   - Opcionalmente, puebla la base de datos:
     ```bash
     php artisan db:seed
     ```

7. **Ejecutar el proyecto con Herd**:
   - Coloca el proyecto en la carpeta configurada de Herd (por defecto, `~/Herd` en macOS).
   - Herd servirá el proyecto automáticamente en una URL local (por ejemplo, `http://nombre-del-proyecto.test`).
   - Alternativamente, usa el servidor de desarrollo de Laravel:
     ```bash
     php artisan serve
     ```
     Esto inicia el servidor en `http://localhost:8000`.

8. **Verificar la instalación**:
   - Abre el navegador y visita la URL de Herd o `http://localhost:8000` para confirmar que el proyecto funciona.

## Notas adicionales

- Si usas Herd y el dominio `.test` no funciona, verifica la configuración con `herd links`.
- Confirma que las extensiones de PHP estén habilitadas:
  ```bash
  php -m
