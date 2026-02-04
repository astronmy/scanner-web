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

        #qr-reader > div {
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
                                    Apuntá la cámara al código QR
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

                    <div class="mt-10 flex justify-center">
                        <button
                            id="btn-new"
                            class="inline-flex items-center px-10 py-3 text-lg font-bold
                                   rounded-xl bg-violet-600 text-white
                                   hover:bg-violet-700
                                   focus:outline-none focus:ring-2 focus:ring-offset-2
                                   focus:ring-violet-500
                                   shadow-lg active:scale-95 transition">
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
        document.addEventListener('DOMContentLoaded', function () {
            const qrRegionId = "qr-reader";
            const html5QrCode = new Html5Qrcode(qrRegionId);

            const status = document.getElementById('qr-status');
            const result = document.getElementById('qr-result');
            const btnNew = document.getElementById('btn-new');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            let isScanning = false;

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
                        result.textContent = 'No se encontró ninguna cámara.';
                        return;
                    }

                    html5QrCode.start(
                        { facingMode: "environment" },
                        config,
                        onScanSuccess,
                        () => {}
                    ).then(() => {
                        isScanning = true;
                    }).catch(err => {
                        console.error(err);
                        result.textContent = 'No se pudo iniciar la cámara.';
                    });
                });
            }

            function onScanSuccess(decodedText) {
                if (!isScanning) return;

                html5QrCode.stop().then(() => {
                    isScanning = false;
                    result.textContent = decodedText;
                    status.textContent = '';
                });
            }

            btnNew.addEventListener('click', () => {
                result.textContent = '';
                status.textContent = 'Apuntá la cámara al código QR';

                if (isScanning) {
                    html5QrCode.stop().finally(startScanner);
                } else {
                    startScanner();
                }
            });

            startScanner();
        });
    </script>

    {{-- Contador --}}
    <div class="fixed bottom-6 right-6">
        <div
            class="w-16 h-16 rounded-full bg-emerald-600 text-white
                   flex items-center justify-center
                   text-lg font-bold shadow-xl
                   border-2 border-white dark:border-gray-800
                   select-none">
            {{ $userScans ?? 0 }}/{{ $scans ?? 0 }}/{{ $total ?? 0 }}
        </div>
    </div>
</x-app-layout>
