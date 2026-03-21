<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Escanear QR
        </h2>
    </x-slot>

    <style>
        #qr-reader video,
        #qr-reader canvas,
        #qr-reader img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }

        #qr-reader>div {
            width: 100% !important;
            height: 100% !important;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-center min-h-[60vh]">
                        <div class="w-full max-w-sm">
                            {{-- Card del lector --}}
                            <div class="bg-gray-100 dark:bg-gray-900 rounded-2xl p-4 shadow-lg flex flex-col items-center">
                                <div
                                    id="qr-reader"
                                    class="w-full aspect-square rounded-xl overflow-hidden bg-black">
                                </div>

                                <p id="qr-status"
                                    class="mt-4 text-sm text-gray-700 dark:text-gray-200 text-center">
                                    Apunt&aacute; la c&aacute;mara al c&oacute;digo QR
                                </p>

                                <p id="qr-result"
                                    class="mt-5 text-xl font-semibold
                                          text-gray-800 dark:text-gray-100
                                          bg-gray-100 dark:bg-gray-800
                                          px-4 py-2 rounded-lg
                                          text-center shadow-sm">
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($newButtonEnabled ?? true)
                        <div class="mt-10 flex justify-center">
                            <button
                                id="btn-new"
                                class="inline-flex items-center px-10 py-3 text-lg font-bold
                                       rounded-xl bg-violet-600 text-white
                                       hover:bg-violet-700
                                       focus:outline-none focus:ring-2 focus:ring-offset-2
                                       focus:ring-violet-500
                                       shadow-lg active:scale-95 transition">
                                <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Nuevo
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="label" value="{!!$label!!}"/>
    {{-- Script del lector --}}
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrRegionId = "qr-reader";
            const html5QrCode = new Html5Qrcode(qrRegionId);

            const status = document.getElementById('qr-status');
            const result = document.getElementById('qr-result');
            const btnNew = document.getElementById('btn-new');
            const userTotals = document.getElementById('userTotals');
            const generalTotals = document.getElementById('generalTotals');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const label = document.getElementById('label').value;
            let isScanning = false;

            async function sendScan(decodedText) {
                try {
                    result.textContent = 'C\u00f3digo detectado, procesando...';
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
                        let control = data.exists == 1
                            ? '<div class="mt-2 text-red-600 dark:text-red-400 font-semibold">{{ e($messageNotFound ?? "La persona ya ingres? previamente") }}</div>'
                            : '';
                        let message = `<center>${data.name}<br>${label}: <br>${data.location}</center>${control}`;

                        result.innerHTML = message;
                        status.textContent = '';
                        result.classList.remove('text-red-600', 'dark:text-red-400');
                        result.classList.add('text-emerald-600', 'dark:text-emerald-400');

                        if (userTotals) {
                            userTotals.textContent = data.user_scans;
                        }
                        if(generalTotals) {
                            let counter = data.scans+'/'+data.total;
                            generalTotals.textContent = counter;
                        }

                    } else if (data && data.message) {
                        result.textContent = data.message;
                    } else {
                        result.textContent = 'No se encuentra el registro';
                    }

                } catch (error) {
                    console.error('Error en el env\u00edo AJAX', error);
                    result.textContent = 'Ocurri\u00f3 un error al procesar el c\u00f3digo.';
                    result.classList.remove('text-emerald-600', 'dark:text-emerald-400');
                    result.classList.add('text-red-600', 'dark:text-red-400');
                }
            }

            function startScanner() {
                const el = document.getElementById('qr-reader');
                const size = Math.min(el.clientWidth, el.clientHeight);

                const config = {
                    fps: 10,
                    qrbox: {
                        width: Math.floor(size * 0.8),
                        height: Math.floor(size * 0.8)
                    }
                };

                Html5Qrcode.getCameras().then(cameras => {
                    if (!cameras.length) {
                        result.textContent = 'No se encontr\u00f3 ninguna c\u00e1mara.';
                        return;
                    }

                    html5QrCode.start({
                            facingMode: "environment"
                        },
                        config,
                        onScanSuccess,
                        () => {}
                    ).then(() => {
                        isScanning = true;
                    }).catch(err => {
                        console.error(err);
                        result.textContent = 'No se pudo iniciar la c\u00e1mara.';
                    });
                });
            }

            function onScanSuccess(decodedText) {
                if (!isScanning) return;

                html5QrCode.stop().then(() => {
                    isScanning = false;
                    result.textContent = decodedText;
                    status.textContent = '';
                    sendScan(decodedText);
                });
            }

            if (btnNew) {
                btnNew.addEventListener('click', () => {
                    result.textContent = '';
                    status.textContent = 'Apunt\u00e1 la c\u00e1mara al c\u00f3digo QR';

                    if (isScanning) {
                        html5QrCode.stop().finally(startScanner);
                    } else {
                        startScanner();
                    }
                });
            }

            startScanner();
        });
    </script>

    <div class="fixed bottom-6 right-6">
        <div id="userTotals"
            class="w-16 h-16 rounded-full
               bg-red-600 text-white
               flex items-center justify-center
               text-lg font-bold shadow-xl
               border-2 border-white dark:border-gray-800
               select-none">
            {{ $userScans ?? 0 }}
        </div>
    </div>

    <div class="fixed bottom-6 left-6 z-50">
        <div id="generalTotals"
            class="min-w-[90px] h-14
               px-4
               rounded-xl
               bg-emerald-600 text-white
               flex items-center justify-center
               text-base font-bold
               shadow-xl
               border-2 border-white dark:border-gray-800
               select-none">
            {{ $scans ?? 0 }}/{{ $total ?? 0 }}
        </div>
    </div>

</x-app-layout>