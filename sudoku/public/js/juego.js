document.addEventListener('DOMContentLoaded', function () {

    // Obtener el contenedor principal para leer las variables de configuración
    const gameContainer = document.getElementById('gameContainer');
    if (!gameContainer) return; // Salir si el contenedor no existe

    // Leer las variables desde los atributos data-*
    const config = {
        horaInicio: parseInt(gameContainer.dataset.horaInicio, 10),
        urlValidar: gameContainer.dataset.urlValidar,
        urlPanel: gameContainer.dataset.urlPanel,
        urlConfetti: gameContainer.dataset.urlConfetti,
        userId: parseInt(gameContainer.dataset.userId, 10)
    };

    // Elementos del DOM
    const timerElement = document.getElementById('timer');
    const switchRanking = document.getElementById('switchRanking');
    const listaGlobal = document.getElementById('listaGlobal');
    const listaPersonal = document.getElementById('listaPersonal');
    const tituloRanking = document.getElementById('tituloRanking');
    const formSudoku = document.getElementById('formSudoku');
    const btnVerificar = document.getElementById('btnVerificar');
    const divVolverPanel = document.getElementById('divVolverPanel');

    // --- LÓGICA DEL TEMPORIZADOR --- https://stackoverflow.com/questions/41896116/javascript-math-floor-time-calculcation

    function actualizarTimer() {
        const ahora = Math.floor(Date.now() / 1000); // Timestamp JS en segundos
        const segundosTranscurridos = ahora - config.horaInicio;

        const minutos = Math.floor(segundosTranscurridos / 60);
        const segundos = segundosTranscurridos % 60;

        const minutosFormateados = String(minutos).padStart(2, '0');
        const segundosFormateados = String(segundos).padStart(2, '0');

        if (timerElement) {
            timerElement.textContent = `${minutosFormateados}:${segundosFormateados}`;
        }
    }

    // Actualizamos el timer inmediatamente y luego cada segundo
    actualizarTimer();
    const intervalID = setInterval(actualizarTimer, 1000);

    // --- LÓGICA DEL SWITCH DE RANKING ---
    if (switchRanking) {
        switchRanking.addEventListener('change', function () {
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
    }

    // --- LÓGICA DEL FORMULARIO + SWEETALERT ---
    if (formSudoku) {
        formSudoku.addEventListener('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            // Cambia el botón inmediatamente
            btnVerificar.type = 'button'; // Para que no vuelva a enviar el form
            btnVerificar.innerHTML = '🔄 Volver a Jugar';
            btnVerificar.classList.remove('btn-light', 'text-primary');
            btnVerificar.classList.add('btn-info', 'text-dark');
            btnVerificar.onclick = () => {
                window.location.href = config.urlPanel;
            };

            // oculta el enlace redundante
            if (divVolverPanel) divVolverPanel.style.display = 'none';

            fetch(config.urlValidar, {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(response => response.json())
            .then(data => {
                clearInterval(intervalID); // Detener el timer en cualquier caso

                if (data.status === 'success') {
                    actualizarRankings(data.rankingGlobal, data.rankingPersonal);
                    Swal.fire({
                        title: '¡VICTORIA!',
                        text: data.msg,
                        icon: 'success',
                        background: '#1a1a2e',
                        color: '#fff',
                        confirmButtonText: 'Volver al Panel',
                        confirmButtonColor: '#6a11cb',
                        backdrop: `rgba(0,0,0,0.8) url("${config.urlConfetti}") left top no-repeat`
                    }).then((result) => {
                        if (result.isConfirmed) window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire({
                        title: 'Fin de la partida',
                        text: data.msg,
                        icon: 'error',
                        background: '#1a1a2e',
                        color: '#fff',
                        confirmButtonText: 'Volver a jugar',
                        confirmButtonColor: '#e94560',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) window.location.href = config.urlPanel;
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
    }

    // --- LÓGICA DE VALIDACIÓN DE CELDAS ---
    document.querySelectorAll('.cell-input').forEach(input => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^1-4]/g, '');
        });
    });

    // --- FUNCIÓN PARA RENDERIZAR RANKINGS ---
    function actualizarRankings(rankingGlobal, rankingPersonal) {
        const listaGlobalEl = document.getElementById('listaGlobal');
        const listaPersonalEl = document.getElementById('listaPersonal');

        listaGlobalEl.innerHTML = '';
        listaPersonalEl.innerHTML = '';

        // Renderizar Ranking Global
        if (!rankingGlobal || rankingGlobal.length === 0) {
            listaGlobalEl.innerHTML = `<li class="list-group-item list-group-item-dark-custom text-center p-4"><span class="opacity-75">Nadie ganó en este nivel.</span><br><strong class="text-warning">¡Sé el primero!</strong></li>`;
        } else {
            rankingGlobal.forEach((puesto, index) => {
                const esUsuarioActual = puesto.usuario_id == config.userId;
                const item = `
                    <li class="list-group-item list-group-item-dark-custom d-flex justify-content-between align-items-center">
                        <div class="text-truncate" style="max-width: 65%;">
                            <span class="fw-bold ${index == 0 ? 'text-warning' : 'text-white'}">#${index + 1}</span>
                            ${esUsuarioActual ? `<strong class="text-info ms-1">${escapeHtml(puesto.nombre_jugador)}</strong>` : `<span class="text-white ms-1">${escapeHtml(puesto.nombre_jugador)}</span>`}
                            <div style="font-size: 0.75rem;" class="text-light ms-4">${new Date(puesto.fecha).toLocaleDateString('es-ES')}</div>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill">⏱ ${puesto.tiempo_segundos}s</span>
                    </li>`;
                listaGlobalEl.innerHTML += item;
            });
        }

        // Renderizar Ranking Personal
        if (!rankingPersonal || rankingPersonal.length === 0) {
            listaPersonalEl.innerHTML = `<li class="list-group-item list-group-item-dark-custom text-center p-4"><span class="opacity-75">Aún no tenés victorias aquí.</span><br><small class="text-info">¡A jugar!</small></li>`;
        } else {
            rankingPersonal.forEach((puesto, index) => {
                const nivelClase = puesto.nivel === 'facil' ? 'bg-success' : (puesto.nivel === 'medio' ? 'bg-warning text-dark' : 'bg-danger');
                const item = `
                    <li class="list-group-item list-group-item-dark-custom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="fw-bold text-info me-3">#${index + 1}</span>
                            <div>
                                <span class="badge ${nivelClase}">${puesto.nivel.charAt(0).toUpperCase() + puesto.nivel.slice(1)}</span>
                                <small class="d-block text-white-50 mt-1">${new Date(puesto.fecha).toLocaleDateString('es-ES')}</small>
                            </div>
                        </div>
                        <span class="badge bg-info text-dark rounded-pill">⏱&nbsp;${puesto.tiempo_segundos}s</span>
                    </li>`;
                listaPersonalEl.innerHTML += item;
            });
        }
    }
// https://stackoverflow.com/questions/6234773/can-i-escape-html-special-chars-in-javascript
    function escapeHtml(unsafe) {
        return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }
});