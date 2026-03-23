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
                    <div class="flex justify-end mb-4">
                        <button
                            type="button"
                            class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[#406075] text-white hover:bg-[#355566] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#406075] shadow-lg"
                            title="Cargar scan manual"
                            id="btn-open-manual-scan-modal">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>
                    </div>
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
    <x-modal name="manual-scan-modal" :show="false" maxWidth="md">
        <div class="p-6 sm:p-8">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Cargar scan manual</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                Complet? el QR/valor y una observaci?n opcional.
            </p>
            <form id="manual-scan-form" class="space-y-4">
                <div>
                    <label for="manual-value" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        QR / Valor
                    </label>
                    <input id="manual-value"
                           name="value"
                           type="text"
                           required
                           class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label for="manual-observation" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Observaci?n (opcional)
                    </label>
                    <textarea id="manual-observation"
                              name="observation"
                              rows="3"
                              class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                </div>
                <p id="manual-scan-error" class="hidden text-xs text-red-600"></p>
                <div class="flex justify-end gap-3">
                    <button type="button"
                        class="inline-flex items-center px-5 py-2.5 text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400"
                        x-on:click="$dispatch('close-modal', 'manual-scan-modal')">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 text-base font-medium text-white bg-[#406075] hover:bg-[#355566] rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#406075]">
                        Guardar manual
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
    {{-- Script del lector --}}
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrRegionId = "qr-reader";
            const html5QrCode = new Html5Qrcode(qrRegionId);
            const autoStartEnabled = @json($autoStartEnabled ?? false);

            const status = document.getElementById('qr-status');
            const result = document.getElementById('qr-result');
            const btnNew = document.getElementById('btn-new');
            const btnOpenManualScanModal = document.getElementById('btn-open-manual-scan-modal');
            const manualScanForm = document.getElementById('manual-scan-form');
            const manualScanError = document.getElementById('manual-scan-error');
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
                } finally {
                    if (autoStartEnabled) {
                        setTimeout(() => {
                            triggerNewScan();
                        }, 700);
                    }
                }
            }

            function triggerNewScan() {
                result.textContent = '';
                status.textContent = 'Apunt\u00e1 la c\u00e1mara al c\u00f3digo QR';

                if (isScanning) {
                    html5QrCode.stop().finally(startScanner);
                } else {
                    startScanner();
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
                    triggerNewScan();
                });
            }

            if (btnOpenManualScanModal) {
                btnOpenManualScanModal.addEventListener('click', () => {
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'manual-scan-modal' }));
                    if (manualScanError) {
                        manualScanError.classList.add('hidden');
                        manualScanError.textContent = '';
                    }
                });
            }

            if (manualScanForm) {
                manualScanForm.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    if (manualScanError) {
                        manualScanError.classList.add('hidden');
                        manualScanError.textContent = '';
                    }

                    const formData = new FormData(manualScanForm);
                    const payload = {
                        value: formData.get('value'),
                        observation: formData.get('observation'),
                    };

                    try {
                        const response = await fetch("{{ route('scanners.manual') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify(payload),
                        });

                        const data = await response.json().catch(() => ({}));
                        if (!response.ok) {
                            throw new Error(data.message || 'No se pudo guardar el scan manual.');
                        }

                        const obs = data.location && data.location !== '?'
                            ? `<div class="mt-1 text-sm text-gray-700 dark:text-gray-300">${data.location}</div>`
                            : '';
                        result.innerHTML = `<center>${data.name}<br>${label}: <br>MANUAL</center>${obs}`;
                        result.classList.remove('text-red-600', 'dark:text-red-400');
                        result.classList.add('text-emerald-600', 'dark:text-emerald-400');
                        status.textContent = '';

                        if (userTotals) userTotals.textContent = data.user_scans ?? userTotals.textContent;
                        if (generalTotals && data.scans != null && data.total != null) {
                            generalTotals.textContent = `${data.scans}/${data.total}`;
                        }

                        manualScanForm.reset();
                        window.dispatchEvent(new CustomEvent('close-modal', { detail: 'manual-scan-modal' }));
                    } catch (error) {
                        if (manualScanError) {
                            manualScanError.textContent = error.message || 'No se pudo guardar el scan manual.';
                            manualScanError.classList.remove('hidden');
                        }
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