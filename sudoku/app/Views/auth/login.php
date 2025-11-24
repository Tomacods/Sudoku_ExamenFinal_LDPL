<!DOCTYPE html> 
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Sudoku</title> 
    <!-- `base_url()` es una función de CodeIgniter que genera la URL base de la app -->
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">
</head>

<body class="dark-mode"> 
    <div class="container py-5 h-100"> <!-- Contenedor principal de Bootstrap para centrar el contenido -->
        <div class="row d-flex justify-content-center align-items-center h-100"> 
            <div class="col-12 col-md-8 col-lg-6 col-xl-5"> <!-- Columna responsiva que define el ancho del formulario en diferentes tamaños de pantalla -->
                
                <div class="card card-dark-custom text-white shadow-lg" style="border-radius: 1rem; background-color: rgba(0, 0, 0, 0.5);">
                    <div class="card-body p-5 text-center"> 

                        <div class="mb-md-2 mt-md-2">

                            <h2 class="fw-bold mb-2 text-uppercase">Sudoku 4x4</h2> 
                            <p class="text-white-50 mb-5">¡Ingresá tu usuario y contraseña!</p> 
                        
                            <!-- Sección para mostrar mensajes de éxito  -->
                            <!-- `session()->getFlashdata()` obtiene un mensaje que solo se muestra una vez y luego se elimina. Es útil para notificaciones. -->
                            <?php if (session()->getFlashdata('mensaje')): ?>
                                <!-- Las clases 'alert' y 'alert-success' de Bootstrap dan estilo a la caja del mensaje -->
                                <div class="alert alert-success text-start text-dark">
                                    <?= session()->getFlashdata('mensaje') ?>
                                </div>
                            <?php endif; ?>

                            <!-- Sección para mostrar mensajes de error  -->
                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger text-start text-dark">
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>

                            <!-- Formulario de inicio de sesión. Los datos se enviarán a la ruta 'login/autenticar' usando el método POST -->
                            <form action="<?= base_url('login/autenticar') ?>" method="post">

                                <div class="form-outline form-white mb-4 text-start">
                                    <label class="form-label text-white-50" for="typeUsuario">Usuario</label>
                                    <input type="text" name="usuario" id="typeUsuario" class="form-control form-control-lg" required /> <!-- Campo para el nombre de usuario. 'required' hace que sea obligatorio -->
                                </div>

                                <div class="form-outline form-white mb-4 text-start">
                                    <label class="form-label text-white-50" for="typePasswordX">Contraseña</label>
                                    <input type="password" name="password" id="typePasswordX" class="form-control form-control-lg" required /> <!-- Campo para la contraseña. 'required' hace que sea obligatorio -->
                                </div>

                                <button class="btn btn-light btn-lg px-5 fw-bold text-primary mt-2" type="submit">Entrar</button>

                            </form>

                        </div>

                        <!-- Sección inferior de la tarjeta con un enlace para registrarse -->
                        <div class="mt-4">
                            <p class="mb-0 text-white-50">¿No tenés cuenta? <a href="<?= base_url('registro') ?>" class="text-white fw-bold text-decoration-none">Registrate acá</a> <!-- Enlace a la página de registro -->
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Inclusión de scripts de JavaScript -->
    <script src="<?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>
