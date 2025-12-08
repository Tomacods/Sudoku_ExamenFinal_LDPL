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
    <main class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">

                <section aria-label="Registro" class="card card-dark-custom text-white shadow-lg" style="border-radius: 1rem; background-color: rgba(0, 0, 0, 0.5);">
                    <div class="card-body p-5 text-center">

                        <div class="mb-md-2 mt-md-2">

                            <header>
                                <h2 class="fw-bold mb-2 text-uppercase">Crear Cuenta</h2>
                            </header>

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
                                        <input type="text" name="nombre" class="form-control form-control-lg" value="<?= old('nombre') ?>" required /> <!--el old() es para que el valor que se ingreso en el campo no se borre si hay un error-->
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
                </section>
            </div>
        </div>
    </main>

    <footer class="footer mt-auto py-3">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-white-50 fs-6">&copy; 2025 SudokuGame</span>
                <a href="https://github.com/Tomacods/Sudoku_ExamenFinal_LDPL" target="_blank" class="text-white opacity-75 text-decoration-none d-flex align-items-center gap-2">
                    <img src="<?= base_url('images/github.svg') ?>" alt="GitHub" width="20" height="20">
                    <span>Tomacods</span>
                </a>
            </div>
        </div>
    </footer>

    <script src="<?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>