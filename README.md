# Sudoku - Examen Final (LDPL)

![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php&logoColor=white)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.x-EF4223?style=flat-square&logo=codeigniter&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat-square&logo=bootstrap&logoColor=white)
![Estado](https://img.shields.io/badge/Estado-Finalizado-success?style=flat-square)

> **Alumno:** Tomás (Tomaco)
> **Materia:** Laboratorio de Programación y Lenguajes

Este repositorio contiene la entrega final de la materia: una aplicación web completa para jugar al **Sudoku (4x4)**. El sistema cuenta con autenticación de usuarios, niveles de dificultad, generación algorítmica de tableros y una interfaz moderna.

---

## Características Principales

El proyecto implementa lógica avanzada y buenas prácticas de ingeniería de software:

* **Algoritmo Inteligente (Backtracking):** El juego no elige tableros al azar. Genera soluciones válidas desde cero y elimina celdas estratégicamente, verificando siempre que el puzzle tenga **solución única**.
* **Seguridad:** Sistema de Login y Registro completo con encriptación de contraseñas (Bcrypt) y manejo seguro de sesiones.
* **Backend:** MySQL para usuarios y registro de partidas ganadas.
* ** Diseño responsivo con **Bootstrap 5**, alertas animadas con **SweetAlert2**.
* ** Configuración dinámica de entorno (`.env`) y URLs, permitiendo ejecutar el proyecto en diferentes servidores (Localhost, Laboratorio, Notebook) sin romper el código.

---

## Requisitos del Sistema

* XAMPP / WAMP (PHP 8.1 o superior).
* Composer (Gestor de dependencias).
* Navegador Web.

---

## Guía de Instalación Rápida

Pasos para levantar el proyecto en un entorno nuevo:

### 1. Clonar el repositorio
Descargá el proyecto en tu carpeta de servidor (ej: `htdocs`).

    git clone [https://github.com/TU_USUARIO/Sudoku_ExamenFinal_LDPL.git](https://github.com/TU_USUARIO/Sudoku_ExamenFinal_LDPL.git)

### 2. Instalar Dependencias (Composer)
**IMPORTANTE:** CodeIgniter 4 requiere instalar sus librerías para funcionar. Abrí una terminal **dentro de la carpeta `sudoku`** y ejecutá:

    cd sudoku
    composer install

*(Esto creará la carpeta `vendor` necesaria).*

### 3. Configurar Entorno (.env)
Por seguridad, las credenciales no se suben al repositorio.

1. En la carpeta `sudoku`, buscá el archivo llamado `env` (sin punto).
2. Hacé una copia y renombrala a **`.env`** (con punto al inicio).
3. Abrilo y configurá tu base de datos (verificá el puerto de tu XAMPP):

    # En archivo .env
    CI_ENVIRONMENT = development

    database.default.hostname = localhost
    database.default.database = sudoku_db
    database.default.username = root
    database.default.password = 
    database.default.port = 3306  # Cambiar a 3007 si es necesario

### 4. Base de Datos
1. Abrí phpMyAdmin.
2. Creá una base de datos llamada **`sudoku_db`**.
3. Importá el archivo SQL provisto en la carpeta `database/` (o ejecutá las migraciones).

### 5. Ejecutar (Vía XAMPP)
1. Abrí el **Panel de Control de XAMPP**.
2. Iniciá los módulos **Apache** y **MySQL** (Botones "Start").
3. Abrí tu navegador y accedé a la siguiente ruta:

    http://localhost/Sudoku_ExamenFinal_LDPL/sudoku/public/

---

## Estructura del Código (MVC)

El proyecto respeta estrictamente el patrón Modelo-Vista-Controlador:

* **`app/Controllers/`**: Maneja el flujo (Login, Renderizado del Tablero).
* **`app/Models/`**: Interactúa con la Base de Datos (`UserModel`).
* **`app/Views/`**: Interfaz de usuario (HTML modularizado).
* **`app/Libraries/`**: Contiene la lógica pesada del algoritmo de generación de Sudokus (Separation of Concerns).
* **`public/js/`**: Lógica del cliente (Validaciones visuales, Fetch API, Timer).

---

## Capturas

*(Espacio reservado para screenshots del Login, Tablero y Modo Oscuro)*

---

Hecho con 💻 y ☕ para el Final de Laboratorio.
