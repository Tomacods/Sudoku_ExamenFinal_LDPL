<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Sudoku</title>
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/auth.css') ?>">
</head>

<body>

    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white card-dark-custom" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <div class="mb-md-5 mt-md-4 pb-5">

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
                                        <label class="form-label" for="typeUsuario">Usuario</label>
                                        <input type="text" name="usuario" id="typeUsuario" class="form-control form-control-lg" required />
                                    </div>

                                    <div class="form-outline form-white mb-4 text-start">
                                        <label class="form-label" for="typePasswordX">Contraseña</label>
                                        <input type="password" name="password" id="typePasswordX" class="form-control form-control-lg" required />
                                    </div>

                                    <button class="btn btn-outline-light btn-lg px-5 mt-3" type="submit">Entrar</button>

                                </form>

                            </div>

                            <div>
                                <p class="mb-0">¿No tenés cuenta? <a href="<?= base_url('registro') ?>" class="text-white-50 fw-bold">Registrate acá</a>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="<?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>