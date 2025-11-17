<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrarse - Sudoku</title>
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Registro de Jugador</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('registro/guardar') ?>" method="post">

                            <div class="row mb-3">
                                <div class="col">
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" class="form-control" required>
                                </div>
                                <div class="col">
                                    <label>Apellido</label>
                                    <input type="text" name="apellido" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Nombre de Usuario</label>
                                <input type="text" name="usuario" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Contraseña</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Registrarse</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        ¿Ya tenés cuenta? <a href="<?= base_url('login') ?>">Iniciá Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>