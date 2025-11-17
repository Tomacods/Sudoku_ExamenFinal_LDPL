<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel Principal - Sudoku</title>
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Sudoku 4x4</a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    Hola, <strong><?= esc($nombre) ?></strong>
                </span>
                <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-danger">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container mt-3">
        <?php if (session()->getFlashdata('mensaje_juego')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
                <strong><?= session()->getFlashdata('mensaje_juego') ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>

    <div class="container">

        <div class="alert alert-info shadow-sm">
            <h4>📜 Tu Historial Reciente</h4>
            <?php if ($ultimaPartida): ?>
                <p class="mb-0">
                    Tu última partida fue el <strong><?= date('d/m/Y H:i', strtotime($ultimaPartida['fecha'])) ?></strong>
                    y el resultado fue:
                    <span class="badge bg-<?= $ultimaPartida['resultado'] == 'victoria' ? 'success' : 'danger' ?>">
                        <?= strtoupper($ultimaPartida['resultado']) ?>
                    </span>
                </p>
            <?php else: ?>
                <p class="mb-0">Todavía no jugaste ninguna partida. ¡Esta será la primera!</p>
            <?php endif; ?>
        </div>

        <div class="card shadow mt-4">
            <div class="card-header bg-dark text-white">
                🎮 Nueva Partida
            </div>
            <div class="card-body text-center">
                <h5 class="card-title">Seleccioná la dificultad</h5>
                <p class="card-text">Esto define cuántos números de ayuda te damos al inicio.</p>

                <form action="<?= base_url('sudoku/crear') ?>" method="post">
                    <div class="btn-group w-100 mb-4" role="group">
                        <input type="radio" class="btn-check" name="dificultad" id="facil" value="facil" checked>
                        <label class="btn btn-outline-success" for="facil">
                            Fácil (8 ayudas)
                        </label>

                        <input type="radio" class="btn-check" name="dificultad" id="medio" value="medio">
                        <label class="btn btn-outline-warning" for="medio">
                            Intermedio (6 ayudas)
                        </label>

                        <input type="radio" class="btn-check" name="dificultad" id="dificil" value="dificil">
                        <label class="btn btn-outline-danger" for="dificil">
                            Difícil (4 ayudas)
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg px-5">¡Jugar Ahora!</button>
                </form>
            </div>
        </div>

    </div>

    <script src="<?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>