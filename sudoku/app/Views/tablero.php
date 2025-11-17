<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sudoku 4x4 - Examen Final</title>
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/sudoku.css') ?>">

</head>

<body class="bg-light">

    <div class="container mt-5 text-center">
        <h2>Sudoku 4x4</h2>
        <p>Nivel: <strong>Fácil</strong> (Ejemplo)</p>

        <form action="<?= base_url('sudoku/validar') ?>" method="post">
            <div class="sudoku-container mt-4">
                <div class="sudoku-grid">
                    <?php
                    // Loop de 0 a 15 (16 celdas)
                    for ($i = 0; $i < 16; $i++):
                    ?>
                        <div class="cell">
                            <?php
                            $valor = $tablero[$i]; // El valor que viene del controlador
                            $esPista = !empty($valor); // ¿Es una pista fija?
                            ?>

                            <div class="cell">
                                <input type="text"
                                    name="c<?= $i ?>"
                                    class="cell-input"
                                    maxlength="1"
                                    autocomplete="off"

                                    /* Si tiene valor, lo mostramos */
                                    value="<?= $valor ?>"

                                    /* Si es pista, que sea de solo lectura */
                                    <?= $esPista ? 'readonly' : '' ?>>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Verificar Solución</button>
                <a href="<?= base_url('sudoku') ?>" class="btn btn-secondary">Reiniciar</a>
            </div>
        </form>
    </div>

    <script src="<?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>