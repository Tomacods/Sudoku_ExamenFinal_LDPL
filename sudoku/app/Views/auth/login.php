<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Sudoku</title>
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

                            <h2 class="fw-bold mb-2 text-uppercase">Sudoku 4x4</h2>
                            <p class="text-white-50 mb-5">¡Ingresá tu usuario y contraseña!</p>

                            <?php if (session()->getFlashdata('mensaje')): ?>
                                <div class="alert alert-success text-start text-dark">
                                    <?= session()->getFlashdata('mensaje') ?>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger text-start text-dark">
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>
                            <form action="<?= base_url('login/autenticar') ?>" method="post">

                                <div class="form-outline form-white mb-4 text-start">
                                    <label class="form-label text-white-50" for="typeUsuario">Usuario</label>
                                    <input type="text" name="usuario" id="typeUsuario" class="form-control form-control-lg" required />
                                </div>

                                <div class="form-outline form-white mb-4 text-start">
                                    <label class="form-label text-white-50" for="typePasswordX">Contraseña</label>
                                    <input type="password" name="password" id="typePasswordX" class="form-control form-control-lg" required />
                                </div>

                                <button class="btn btn-light btn-lg px-5 fw-bold text-primary mt-2" type="submit">Entrar</button>

                            </form>

                        </div>

                        <div class="mt-4">
                            <p class="mb-0 text-white-50">¿No tenés cuenta? <a href="<?= base_url('registro') ?>" class="text-white fw-bold text-decoration-none">Registrate acá</a>
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