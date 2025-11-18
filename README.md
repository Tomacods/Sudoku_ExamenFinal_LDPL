# 🧩 Sudoku 4x4 - Trabajo Final Integrador

> **Alumno:** Tomás Da Silva  
> **Materia:** Laboratorio de Programación y Lenguajes  
> **Año:** 2025

Aplicación web desarrollada en **PHP** utilizando el framework **CodeIgniter 4**. Implementa un juego de Sudoku de 4x4 con sistema de usuarios, historial de partidas, ranking de tiempos y validación en tiempo real.

## 🚀 Tecnologías Utilizadas

* **Backend:** PHP 8.1+, CodeIgniter 4
* **Frontend:** HTML5, Bootstrap 5, SweetAlert2 (Alertas AJAX)
* **Base de Datos:** MySQL (XAMPP)
* **Gestor de Paquetes:** Composer

---

## 🛠️ Guía de Instalación y Despliegue

Sigue estos pasos para levantar el proyecto en tu entorno local (XAMPP).

### 1. Requisitos Previos
* Tener instalado **XAMPP** (o cualquier servidor Apache + MySQL).
* Tener habilitadas las extensiones `intl` y `mbstring` en el `php.ini`.

### 2. Instalación de Archivos
Clona el repositorio o descomprime la carpeta del proyecto dentro del directorio público del servidor:
* **Ruta XAMPP:** `C:\xampp\htdocs\sudoku`

### 3. Base de Datos 🗄️
1.  Abrir **phpMyAdmin** (`http://localhost/phpmyadmin`).
2.  Crear una nueva base de datos llamada `test` (o el nombre que prefieras).
3.  Importar el archivo `base_de_datos.sql` que se encuentra en la raíz de este proyecto.
    * *Esto creará automáticamente las tablas `usuarios` y `partidas`.*

### 4. Configuración del Entorno (`.env`) ⚙️
1.  En la carpeta raíz, busca el archivo `env` y renombralo a `.env` (con el punto adelante).
2.  Abre el archivo y configura la conexión a la base de datos para que coincida con la tuya:

```ini
database.default.hostname = localhost
database.default.database = sudoku_db  <-- El nombre de DB
database.default.username = root
database.default.password =       <-- Vacío en XAMPP por defecto
database.default.DBDriver = MySQLi