<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba Bootstrap - Sudoku</title>

    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
</head>

<body>

    <div class="container mt-5">
        <div class="card text-center shadow">
            <div class="card-header bg-primary text-white">
                ¡Todo listo, dev!
            </div>
            <div class="card-body">
                <h5 class="card-title">Bootstrap está corriendo localmente</h5>
                <p class="card-text">Si ves esto lindo y ordenado, ya tenés la base para tu Sudoku.</p>

                <a href="<?= base_url('sudoku') ?>" class="btn btn-primary btn-lg">
                    🎮 Ir al Sudoku
                </a>
            </div>
            <div class="card-footer text-muted">
                CodeIgniter 4 + Bootstrap 5
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">¡Funciona!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    El Javascript de Bootstrap también está conectado.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>