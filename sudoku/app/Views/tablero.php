<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sudoku 4x4 - Jugando</title>
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/juego.css') ?>">
    <script src="<?= base_url('js/sweetalert2.all.min.js') ?>"></script>
</head>

<body class="dark-mode">

    <div id="gameContainer" class="container mt-5" 
        data-hora-inicio="<?= $hora_inicio ?>"
        data-url-validar="<?= base_url('sudoku/validar') ?>"
        data-url-panel="<?= base_url('panel') ?>"
        data-user-id="<?= session('id') ?>"
    >
        <div class="row justify-content-center">

            <div class="col-md-7 text-center">
                <div class="p-4 rounded shadow-lg" style="background-color: rgba(0,0,0,0.3);">
                    <h2 class="text-white fw-bold">Sudoku 4x4</h2>
                    <p class="text-white">Nivel: <strong class="text-uppercase text-warning"><?= $dificultad ?></strong></p>
                    <p class="text-white mb-0">Tiempo: <span id="timer" class="fw-bold text-warning">00:00</span></p>

                    <form id="formSudoku" action="<?= base_url('sudoku/validar') ?>" method="post">
                        <div class="sudoku-container mt-4">
                            <div class="sudoku-grid" style="border-color: #444;">
                                <?php for ($i = 0; $i < 16; $i++):
                                    $valor = $tablero[$i];
                                    $esPista = !empty($valor);
                                ?>
                                    <div class="cell">
                                        <input type="text" name="c<?= $i ?>"
                                            class="cell-input" maxlength="1" autocomplete="off" pattern="[1-4]"
                                            value="<?= $valor ?>"
                                            <?= $esPista ? 'readonly' : '' ?>>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div class="mt-4 mb-3">
                            <button type="submit" id="btnVerificar" class="btn btn-light btn-lg px-5 fw-bold text-primary shadow">
                                Verificar Solución
                            </button>
                            <div class="mt-3" id="divVolverPanel">
                                <a href="<?= base_url('panel') ?>" class="text-white text-decoration-none">
                                    <small> Volver al Panel</small>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-lg border-0" style="background-color: rgba(255,255,255,0.1);">

                    <div class="card-header bg-transparent border-bottom border-light text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0" id="tituloRanking">👤 Mis Tiempos</h5>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="switchRanking" checked>
                                <label class="form-check-label small text-white" for="switchRanking">Ver Míos</label>
                            </div>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush bg-transparent d-none" id="listaGlobal">
                        <?php if (empty($rankingGlobal)): ?>
                            <li class="list-group-item list-group-item-dark-custom text-center p-4">
                                <span class="opacity-75">Nadie ganó en este nivel.</span><br>
                                <strong class="text-warning">¡Sé el primero!</strong>
                            </li>
                        <?php else: ?>
                            <?php foreach ($rankingGlobal as $index => $puesto): ?>
                                <li class="list-group-item list-group-item-dark-custom d-flex justify-content-between align-items-center">
                                    <div class="text-truncate" style="max-width: 65%;">
                                        <span class="fw-bold <?= $index == 0 ? 'text-warning' : 'text-white' ?>">#<?= $index + 1 ?></span>
                                        <?php if ($puesto['usuario_id'] == session('id')): ?>
                                            <strong class="text-info ms-1"><?= esc($puesto['nombre_jugador']) ?></strong>
                                        <?php else: ?>
                                            <span class="text-white ms-1"><?= esc($puesto['nombre_jugador']) ?></span>
                                        <?php endif; ?>
                                        <div style="font-size: 0.75rem;" class="text-light ms-4">
                                            <?= date('d/m/Y', strtotime($puesto['fecha'])) ?>
                                        </div>
                                    </div>
                                    <span class="badge bg-warning text-dark rounded-pill">
                                        ⏱ <?= $puesto['tiempo_segundos'] ?>s
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>

                    <ul class="list-group list-group-flush bg-transparent" id="listaPersonal">
                        <?php if (empty($rankingPersonal)): ?>
                            <li class="list-group-item list-group-item-dark-custom text-center p-4">
                                <span class="opacity-75">Aún no tenés victorias aquí.</span><br>
                                <small class="text-info">¡A jugar!</small>
                            </li>
                        <?php else: ?>
                            <?php foreach ($rankingPersonal as $index => $puesto): ?>
                                <li class="list-group-item list-group-item-dark-custom d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold text-info me-3">#<?= $index + 1 ?></span>
                                        <div>
                                            <span class="badge 
                                                <?php if ($puesto['nivel'] == 'facil') echo 'bg-success';
                                                elseif ($puesto['nivel'] == 'medio') echo 'bg-warning text-dark';
                                                else echo 'bg-danger'; ?>">
                                                <?= ucfirst($puesto['nivel']) ?>
                                            </span>
                                            <small class="d-block text-white-50 mt-1">
                                                <?= date('d/m/Y', strtotime($puesto['fecha'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge bg-info text-dark rounded-pill">
                                        ⏱&nbsp;<?= $puesto['tiempo_segundos'] ?>s
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
    <script src="<?= base_url('js/juego.js') ?>"></script>
</body>
</html>