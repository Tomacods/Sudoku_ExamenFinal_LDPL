<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrarse - Sudoku</title>
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">
</head>

<body class="dark-mode">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                <div class="card card-dark-custom text-white shadow-lg" style="border-radius: 1rem; background-color: rgba(0, 0, 0, 0.5);">
                    <div class="card-body p-5 text-center">

                        <div class="mb-md-2 mt-md-2">

                            <h2 class="fw-bold mb-2 text-uppercase">Crear Cuenta</h2>
                            <p class="text-white-50 mb-4">Unite al desafío del Sudoku 4x4</p>

                            <?php if (session()->has('errors')): ?>
                                <div class="alert alert-danger text-start border-0 shadow-sm" style="background-color: #ffcccc; color: #990000;">
                                    <ul class="mb-0 ps-3">
                                        <?php foreach (session('errors') as $error): ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form action="<?= base_url('registro/guardar') ?>" method="post">

                                <div class="row">
                                    <div class="col-md-6 mb-3 text-start">
                                        <label class="form-label text-white-50">Nombre</label>
                                        <input type="text" name="nombre" class="form-control form-control-lg" value="<?= old('nombre') ?>" required />
                                    </div>
                                    <div class="col-md-6 mb-3 text-start">
                                        <label class="form-label text-white-50">Apellido</label>
                                        <input type="text" name="apellido" class="form-control form-control-lg" value="<?= old('apellido') ?>" required />
                                    </div>
                                </div>

                                <div class="form-outline form-white mb-3 text-start">
                                    <label class="form-label text-white-50">Email</label>
                                    <input type="email" name="email" class="form-control form-control-lg" value="<?= old('email') ?>" required />
                                </div>

                                <div class="form-outline form-white mb-3 text-start">
                                    <label class="form-label text-white-50">Usuario</label>
                                    <input type="text" name="usuario" class="form-control form-control-lg" value="<?= old('usuario') ?>" required />
                                </div>

                                <div class="form-outline form-white mb-4 text-start">
                                    <label class="form-label text-white-50">Contraseña</label>
                                    <input type="password" name="password" class="form-control form-control-lg" required />
                                </div>

                                <button class="btn btn-light btn-lg px-5 fw-bold text-primary mt-2" type="submit">
                                    Registrarse
                                </button>

                            </form>

                        </div>

                        <div class="mt-4">
                            <p class="mb-0 text-white-50">¿Ya tenés cuenta? <a href="<?= base_url('login') ?>" class="text-white fw-bold text-decoration-none">Ingresá acá</a>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>