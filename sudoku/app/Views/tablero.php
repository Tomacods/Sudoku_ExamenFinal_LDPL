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

    <div class="container mt-5">
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
                            <h5 class="mb-0" id="tituloRanking">🌍 Ranking Global</h5>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="switchRanking">
                                <label class="form-check-label small text-white" for="switchRanking">Ver Míos</label>
                            </div>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush bg-transparent" id="listaGlobal">
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

                    <ul class="list-group list-group-flush bg-transparent d-none" id="listaPersonal">
                        <?php if (empty($rankingPersonal)): ?>
                            <li class="list-group-item list-group-item-dark-custom text-center p-4">
                                <span class="opacity-75">Aún no tenés victorias aquí.</span><br>
                                <small class="text-info">¡A jugar se ha dicho!</small>
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

    <script>
        // 0. LÓGICA DEL TEMPORIZADOR
        const horaInicio = <?= $hora_inicio ?>; // Timestamp PHP
        const timerElement = document.getElementById('timer');

        function actualizarTimer() {
            const ahora = Math.floor(Date.now() / 1000); // Timestamp JS en segundos
            const segundosTranscurridos = ahora - horaInicio;

            const minutos = Math.floor(segundosTranscurridos / 60);
            const segundos = segundosTranscurridos % 60;

            const minutosFormateados = String(minutos).padStart(2, '0');
            const segundosFormateados = String(segundos).padStart(2, '0');

            timerElement.textContent = `${minutosFormateados}:${segundosFormateados}`;
        }

        // Actualizamos el timer inmediatamente al cargar la página
        actualizarTimer();

        const intervalID = setInterval(actualizarTimer, 1000);


        // 1. LÓGICA DEL SWITCH DE RANKING
        const switchRanking = document.getElementById('switchRanking');
        const listaGlobal = document.getElementById('listaGlobal');
        const listaPersonal = document.getElementById('listaPersonal');
        const tituloRanking = document.getElementById('tituloRanking');

        switchRanking.addEventListener('change', function() {
            if (this.checked) {
                // Mostrar Personal
                listaGlobal.classList.add('d-none');
                listaPersonal.classList.remove('d-none');
                tituloRanking.innerHTML = '👤 Mis Tiempos';
            } else {
                // Mostrar Global
                listaPersonal.classList.add('d-none');
                listaGlobal.classList.remove('d-none');
                tituloRanking.innerHTML = '🌍 Ranking Global';
            }
        });

        // 2. LÓGICA DEL FORMULARIO + SWEETALERT DARK
        document.getElementById('formSudoku').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            const btnVerificar = document.getElementById('btnVerificar');
            const divVolverPanel = document.getElementById('divVolverPanel');

            // Cambiamos el botón inmediatamente
            btnVerificar.type = 'button'; // Para que no vuelva a enviar el form
            btnVerificar.innerHTML = '🔄 Volver a Jugar';
            btnVerificar.classList.remove('btn-light', 'text-primary');
            btnVerificar.classList.add('btn-info', 'text-dark');
            btnVerificar.onclick = () => {
                window.location.href = '<?= base_url('panel') ?>';
            };

            // Ocultamos el enlace redundante
            divVolverPanel.style.display = 'none';

            fetch("<?= base_url('sudoku/validar') ?>", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {


                        // Si ganamos, limpiamos el progreso guardado para esta dificultad
                        // para no cargar un tablero resuelto la próxima vez.
                        localStorage.removeItem(`sudoku_progreso_<?= $dificultad ?>`);


                        clearInterval(intervalID);


                        Swal.fire({
                            title: '¡VICTORIA!',
                            text: data.msg,
                            icon: 'success',
                            background: '#1a1a2e',
                            color: '#fff',
                            confirmButtonText: 'Volver al Panel',
                            confirmButtonColor: '#6a11cb', // Botón Violeta
                            backdrop: `rgba(0,0,0,0.8) url("<?= base_url('images/confetti.gif') ?>") left top no-repeat` // Opcional: fondo oscuro
                        }).then((result) => {
                            if (result.isConfirmed) window.location.href = data.redirect;
                        });

                    } else {


                        clearInterval(intervalID);


                        Swal.fire({
                            title: 'Fin de la partida',
                            text: data.msg,
                            icon: 'error',
                            background: '#1a1a2e',
                            color: '#fff',
                            confirmButtonText: 'Seguir Intentando',
                            confirmButtonColor: '#e94560' // Botón Rojo Neón
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Fallo de conexión',
                        icon: 'warning',
                        background: '#1a1a2e',
                        color: '#fff'
                    });
                });
        });

        // 3. LÓGICA DE VALIDACIÓN DE CELDAS EN TIEMPO REAL
        document.querySelectorAll('.cell-input').forEach(input => {
            // Se ejecuta cada vez que el usuario escribe algo en una celda
            input.addEventListener('input', function(e) {
                // this.value es el contenido actual de la celda
                // .replace(/[^1-4]/g, '') busca cualquier caracter que NO SEA (^) 1, 2, 3 o 4
                // y lo reemplaza por nada (''). La 'g' asegura que reemplace todas las ocurrencias.
                // Esto elimina letras, el cero, símbolos, etc.
                this.value = this.value.replace(/[^1-4]/g, '');
            });
        });

        // 4. LÓGICA DE AUTOGUARDADO (LOCALSTORAGE)
        (function() {
            const storageKey = `sudoku_progreso_<?= $dificultad ?>`;
            const inputs = document.querySelectorAll('.cell-input');

            // --- FUNCIÓN PARA CARGAR EL PROGRESO ---
            function cargarProgreso() {
                const progresoGuardado = localStorage.getItem(storageKey);
                if (progresoGuardado) {
                    const valores = JSON.parse(progresoGuardado);
                    inputs.forEach((input, index) => {
                        // Solo rellenamos las celdas que el usuario puede editar
                        if (!input.readOnly) {
                            input.value = valores[index] || '';
                        }
                    });
                }
            }

            // --- FUNCIÓN PARA GUARDAR EL PROGRESO ---
            function guardarProgreso() {
                const progresoActual = Array.from(inputs).map(input => input.value);
                localStorage.setItem(storageKey, JSON.stringify(progresoActual));
            }

            // --- EVENTOS ---
            document.addEventListener('DOMContentLoaded', cargarProgreso); // Cargar al iniciar
            inputs.forEach(input => input.addEventListener('input', guardarProgreso)); // Guardar al escribir
        })();
    </script>

    <script src="<?= base_url('bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>