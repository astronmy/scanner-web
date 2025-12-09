<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Escanear QR
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-center min-h-[60vh]">
                        <div class="w-full max-w-sm">
                            {{-- Card del lector --}}
                            <div class="bg-gray-100 dark:bg-gray-900 rounded-2xl p-4 shadow-lg flex flex-col items-center">
                                <div id="qr-reader"
                                    class="w-full aspect-square rounded-xl overflow-hidden bg-black">
                                </div>

                                <p id="qr-status"
                                    class="mt-4 text-sm text-gray-700 dark:text-gray-200 text-center">
                                    Apuntá la cámara al código QR
                                </p>

                                <p id="qr-result"
                                    class="mt-5 text-2xl text-xl font-semibold
          text-gray-800 dark:text-gray-100
          bg-gray-100 dark:bg-gray-800
          px-4 py-2 rounded-lg
          text-center shadow-sm">
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-10 flex justify-center">
                        <button id="btn-new"
                            class="inline-flex items-center px-10 py-3 text-lg font-bold
               rounded-xl
               bg-violet-600 text-white
               hover:bg-violet-700
               text-base
               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500
               shadow-lg active:scale-95 transition mt-4
               rounded p-2 bg-red-600 text-white hover:bg-red-700
          ">
                            Nuevo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script del lector --}}
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrRegionId = "qr-reader";
            const html5QrCode = new Html5Qrcode(qrRegionId);
            const status = document.getElementById('qr-status');
            const result = document.getElementById('qr-result');
            const btnNew = document.getElementById('btn-new');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            let isScanning = false; // 👈 estado interno

            async function sendScan(decodedText) {
                try {
                    result.textContent = 'Código detectado, procesando...';
                    result.classList.remove(
                        'text-gray-700', 'dark:text-gray-200',
                        'text-red-600', 'dark:text-red-400',
                        'text-emerald-600', 'dark:text-emerald-400'
                    );
                    result.classList.add('text-emerald-600', 'dark:text-emerald-400');

                    const response = await fetch("{{ route('scanners.storage') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            value: decodedText,
                        }),
                    });

                    if (!response.ok) {
                        throw new Error('Error HTTP: ' + response.status);
                    }

                    const data = await response.json().catch(() => ({}));

                    if (data && data.location && data.name) {
                        let control = data.exists == 1 ? 'El invitado ya ingreso previamente' : '';
                        let message = `<center>${data.name}<br>Mesa: <br>${data.location}</center><br><br>
                        ${control}`;

                        result.innerHTML = message;
                        status.textContent = '';
                    } else if (data && data.message) {
                        result.textContent = data.message;
                    } else {
                        result.textContent = 'No se encuentra el registro';
                    }

                } catch (error) {
                    console.error('Error en el envío AJAX', error);
                    result.textContent = 'Ocurrió un error al procesar el código.';
                    result.classList.remove('text-emerald-600', 'dark:text-emerald-400');
                    result.classList.add('text-red-600', 'dark:text-red-400');
                }
            }

            function onScanSuccess(decodedText, decodedResult) {
                // Detenemos sólo si está escaneando
                if (!isScanning) return;

                html5QrCode.stop().then(() => {
                    isScanning = false;
                    sendScan(decodedText);
                }).catch(err => {
                    console.error('Error al detener la cámara', err);
                });
            }

            function onScanError(errorMessage) {
                // silencioso
            }

            function startScanner() {
                Html5Qrcode.getCameras().then(cameras => {
                    if (!cameras || !cameras.length) {
                        result.textContent = 'No se encontró ninguna cámara.';
                        result.classList.remove('text-gray-700', 'dark:text-gray-200');
                        result.classList.add('text-red-600', 'dark:text-red-400');
                        return;
                    }

                    const config = {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    };

                    html5QrCode
                        .start({
                            facingMode: "environment"
                        }, config, onScanSuccess, onScanError)
                        .then(() => {
                            isScanning = true; // 👈 ahora sí está escaneando
                        })
                        .catch(err => {
                            console.error('Error al iniciar la cámara', err);
                            result.textContent = 'No se pudo iniciar la cámara.';
                            result.classList.remove('text-gray-700', 'dark:text-gray-200');
                            result.classList.add('text-red-600', 'dark:text-red-400');
                        });

                }).catch(err => {
                    console.error('Error al obtener cámaras', err);
                    result.textContent = 'No se pudo acceder a la cámara.';
                    result.classList.remove('text-gray-700', 'dark:text-gray-200');
                    result.classList.add('text-red-600', 'dark:text-red-400');
                });
            }

            function restartScanner() {
                // Reseteo textos
                result.textContent = '';
                status.textContent = 'Apuntá la cámara al código QR';
                result.classList.remove(
                    'text-emerald-600', 'dark:text-emerald-400',
                    'text-red-600', 'dark:text-red-400'
                );

                // Si está escaneando, primero detenemos, si no, solo arrancamos
                if (isScanning) {
                    html5QrCode.stop()
                        .then(() => {
                            isScanning = false;
                            startScanner();
                        })
                        .catch(err => {
                            console.warn('No se pudo detener (quizás no estaba activo):', err);
                            isScanning = false;
                            startScanner();
                        });
                } else {
                    startScanner();
                }
            }

            btnNew.addEventListener('click', function() {
                restartScanner();
            });

            startScanner();
        });
    </script>

</x-app-layout>