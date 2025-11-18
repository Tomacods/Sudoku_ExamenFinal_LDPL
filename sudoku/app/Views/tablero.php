<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sudoku 4x4 - Examen Final</title>
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/sudoku.css') ?>">

</head>

<body class="bg-light">

    <div class="container mt-4">
        <div class="row">

            <div class="col-md-8 text-center">
                <h2>Sudoku 4x4</h2>
                <p>Nivel: <strong class="text-uppercase"><?= $dificultad ?></strong></p>

                <form action="<?= base_url('sudoku/validar') ?>" method="post" id="formSudoku">

                    <div class="sudoku-container mt-4">
                        <div class="sudoku-grid">
                            <?php for ($i = 0; $i < 16; $i++):
                                $valor = $tablero[$i];
                                $esPista = !empty($valor);
                            ?>
                                <div class="cell">
                                    <input type="text" name="c<?= $i ?>"
                                        class="cell-input" maxlength="1" autocomplete="off"
                                        value="<?= $valor ?>"
                                        <?= $esPista ? 'readonly' : '' ?>>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="mt-4 mb-5">
                        <button type="submit" class="btn btn-primary btn-lg shadow">Verificar Solución</button>
                        <a href="<?= base_url('panel') ?>" class="btn btn-outline-secondary">Volver al Panel</a>
                    </div>
                </form>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        🏆 Mis Mejores Tiempos
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php if (empty($mejoresPartidas)): ?>
                            <li class="list-group-item text-muted text-center p-4">
                                Todavia no tenés victorias registradas. <br>
                                ¡Ganá esta para aparecer acá!
                            </li>
                        <?php else: ?>
                            <?php foreach ($mejoresPartidas as $index => $partida): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="fw-bold">#<?= $index + 1 ?></span>
                                        <small class="text-muted ms-2"><?= date('d/m/Y', strtotime($partida['fecha'])) ?></small>
                                        <br>
                                        <span class="badge bg-secondary"><?= ucfirst($partida['nivel']) ?></span>
                                    </div>
                                    <span class="badge bg-primary rounded-pill fs-6">
                                        <?= $partida['tiempo_segundos'] ?> seg
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <script src="<?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <script src="<?= base_url('js/sweetalert2.all.min.js') ?>"></script>

    <script>
        // 1. Escuchamos el envío del formulario
        document.getElementById('formSudoku').addEventListener('submit', function(e) {
            e.preventDefault(); // ¡ALTO! Detenemos la recarga de página

            // 2. Preparamos los datos
            let formData = new FormData(this);

            // 3. Enviamos por AJAX (Fetch)
            fetch("<?= base_url('sudoku/validar') ?>", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(response => response.json()) // Convertimos la respuesta a JSON
                .then(data => {

                    // 4. Reaccionamos según el resultado
                    if (data.status === 'success') {

                        Swal.fire({
                            title: '¡Excelente!',
                            text: data.msg,
                            icon: 'success',
                            confirmButtonText: 'Ir al Panel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = data.redirect; // Nos vamos al panel
                            }
                        });

                    } else {

                        Swal.fire({
                            title: 'Incorrecto',
                            text: data.msg,
                            icon: 'error',
                            confirmButtonText: 'Seguir Intentando'
                        });

                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Algo salió mal con el servidor', 'error');
                });
        });
    </script>
</body>

</html>