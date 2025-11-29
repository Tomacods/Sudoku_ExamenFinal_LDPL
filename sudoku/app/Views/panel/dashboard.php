<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel Principal - Sudoku</title>
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/panel.css') ?>">
</head>

<body class="dark-mode">

    <nav class="navbar navbar-expand-lg navbar-dark navbar-transparent mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold">🧩 Sudoku 4x4</a>
            <div class="d-flex align-items-center">
                <span class="navbar-text text-white me-3">
                    Hola, <strong><?= esc($nombre) ?></strong>
                </span>
                <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-outline-light">Salir</a> 
            </div>
        </div>
    </nav>

    <div class="container">

        <?php if (session()->getFlashdata('mensaje_juego')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-lg bg-success text-white border-0" role="alert">
                <strong><?= session()->getFlashdata('mensaje_juego') ?></strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card card-dark-custom text-white mb-4 shadow-lg" style="background-color: rgba(0,0,0,0.4); border: none;">
            <div class="card-body">
                <h4 class="card-title">📜 Historial Reciente</h4>
                <?php if ($ultimaPartida): ?>
                    <p class="mb-0 opacity-75">
                        Tu última partida fue el <strong><?= date('d/m/Y H:i', strtotime($ultimaPartida['fecha'])) ?></strong>
                        y el resultado fue:
                        <span class="badge bg-<?= $ultimaPartida['resultado'] == 'victoria' ? 'success' : 'danger' ?>">
                            <?= strtoupper($ultimaPartida['resultado']) ?>
                        </span>
                    </p>
                <?php else: ?>
                    <p class="mb-0 opacity-75">Todavía no jugaste ninguna partida. ¡Esta será la primera!</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card card-dark-custom text-white shadow-lg" style="background-color: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
            <div class="card-header bg-transparent border-bottom border-light text-center">
                <h3 class="fw-bold mb-0">🎮 Nueva Partida</h3>
            </div>
            <div class="card-body text-center p-5">

                <form action="<?= base_url('sudoku/crear') ?>" method="post">
                    <h5 class="card-title mb-4 opacity-75">Seleccioná la dificultad</h5>

                    <div class="contenedor-globos">
                        <input type="radio" class="btn-check" name="dificultad" id="facil" value="facil" checked autocomplete="off">
                        <label class="globo-dificultad facil" for="facil">
                            <span>Fácil</span>
                            <small>8 ayudas</small>
                        </label>

                        <input type="radio" class="btn-check" name="dificultad" id="medio" value="medio" autocomplete="off">
                        <label class="globo-dificultad medio" for="medio">
                            <span>Medio</span>
                            <small>6 ayudas</small>
                        </label>

                        <input type="radio" class="btn-check" name="dificultad" id="dificil" value="dificil" autocomplete="off">
                        <label class="globo-dificultad dificil" for="dificil">
                            <span>Difícil</span>
                            <small>4 ayudas</small>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-light btn-lg px-5 mt-4 fw-bold text-primary shadow">
                        🚀 ¡Jugar Ahora!
                    </button>
                </form>
            </div>
        </div>

    </div>

    <script src="<?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>