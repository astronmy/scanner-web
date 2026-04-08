<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Listado — {{ $event->name }}
        </h2>
    </x-slot>

    @php
        $scanBaseUrl = url('/scanners/list/' . $event->id . '/scans');
    @endphp

    <style>
        /* Ocultar Estado y Registrado solo en móvil portrait (ancho estrecho + vertical) */
        @media (max-width: 767px) and (orientation: portrait) {
            .list-col-wide-only { display: none !important; }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex flex-wrap items-center gap-2">
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md bg-[#406075] text-white hover:bg-[#355566]">
                    <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                    Volver al dashboard
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 overflow-x-auto">
                    @if($listRows->isNotEmpty())
                        <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
                            <div class="min-w-0 max-w-md flex-1">
                                <label for="list-grid-search" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Buscar</label>
                                <input type="search"
                                       id="list-grid-search"
                                       autocomplete="off"
                                       placeholder="ID, QR, observaciones, estado…"
                                       class="block w-full max-w-md rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-2">
                            </div>
                            <button type="button"
                                id="list-btn-open-manual-scan-modal"
                                class="inline-flex items-center justify-center w-12 h-12 shrink-0 rounded-full bg-[#6a6b6b] text-white hover:bg-[#5a5a5a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6a6b6b] shadow-lg"
                                title="Cargar scan manual">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </div>
                    @else
                        <div class="mb-4 flex justify-end">
                            <button type="button"
                                id="list-btn-open-manual-scan-modal"
                                class="inline-flex items-center justify-center w-12 h-12 shrink-0 rounded-full bg-[#6a6b6b] text-white hover:bg-[#5a5a5a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6a6b6b] shadow-lg"
                                title="Cargar scan manual">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </div>
                    @endif
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">ID</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">QR</th>
                                <th class="px-3 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">Observaciones</th>
                                <th class="list-col-wide-only px-3 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">Estado</th>
                                <th class="list-col-wide-only px-3 py-2 text-left font-semibold text-gray-700 dark:text-gray-200">Registrado</th>
                                <th class="px-3 py-2 text-right font-semibold text-gray-700 dark:text-gray-200">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($listRows as $gridRow)
                                @php
                                    $gAssignment = $gridRow->assignment;
                                    $gScan = $gridRow->scan;
                                    $gPending = $gridRow->kind === 'pending';
                                    $gOrphan = $gridRow->kind === 'orphan';
                                    $gDup = $gridRow->is_duplicate;
                                    $gTable = $gOrphan
                                        ? (filled($gScan->id_list) ? $gScan->id_list : '—')
                                        : $gAssignment->table_number;
                                    $gQr = $gOrphan
                                        ? (filled($gScan->qr_list) ? $gScan->qr_list : $gScan->value)
                                        : $gAssignment->guest_name;
                                    $gObs = $gOrphan
                                        ? (string) ($gScan->observations ?? '')
                                        : (string) ($gAssignment->observations ?? '');
                                    $gScannedAt = $gScan?->scanned_at;
                                    $gSearchExtra = trim(
                                        ($gOrphan ? 'sin listado manual fuera ' : '')
                                        . ($gDup ? 'duplicado ' : '')
                                    );
                                    $gSearch = \Illuminate\Support\Str::lower(trim(
                                        (string) $gTable . ' ' .
                                        (string) $gQr . ' ' .
                                        (string) $gObs . ' ' .
                                        ($gPending ? 'pendiente' : 'registrado') . ' ' .
                                        $gSearchExtra . ' ' .
                                        ($gScannedAt ? $gScannedAt->format('d/m/Y H:i d-m-Y') : '')
                                    ));
                                @endphp
                                <tr @if($gAssignment) data-assignment-id="{{ $gAssignment->id }}" @endif
                                    class="list-grid-data-row {{ $gPending
                                        ? 'bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800/90 border-l-4 border-gray-200 dark:border-gray-600'
                                        : 'bg-emerald-200/90 dark:bg-emerald-900/50 hover:bg-emerald-300/95 dark:hover:bg-emerald-800/55 border-l-4 border-emerald-600 dark:border-emerald-400' }} transition-colors duration-150"
                                    data-search="{{ e($gSearch) }}">
                                    <td class="px-3 py-2 whitespace-nowrap">{{ $gTable }}</td>
                                    <td class="px-3 py-2 break-all max-w-xs qr-display-cell">{{ $gQr }}</td>
                                    <td class="px-3 py-2 text-gray-600 dark:text-gray-300 max-w-xs break-words obs-display-cell">{{ $gObs !== '' ? $gObs : '—' }}</td>
                                    <td class="list-col-wide-only px-3 py-2 whitespace-nowrap estado-cell">
                                        @if($gPending)
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-200">Pendiente</span>
                                        @else
                                            <span class="inline-flex flex-wrap items-center gap-1">
                                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">Registrado</span>
                                                @if($gOrphan)
                                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-900 dark:bg-amber-900/30 dark:text-amber-100" title="No hay fila en el listado importado para este escaneo">Sin listado</span>
                                                @endif
                                                @if($gDup)
                                                    <span class="inline-flex items-center rounded-full bg-violet-100 px-2 py-0.5 text-xs font-medium text-violet-800 dark:bg-violet-900/40 dark:text-violet-200">Duplicado</span>
                                                @endif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="list-col-wide-only px-3 py-2 whitespace-nowrap text-xs last-scan-cell">
                                        {{ $gScannedAt ? $gScannedAt->format('d/m/Y H:i') : '—' }}
                                    </td>
                                    <td class="px-3 py-2 text-right whitespace-nowrap actions-cell">
                                        @if($gPending)
                                            <button type="button"
                                                class="js-list-scan inline-flex items-center justify-center w-9 h-9 rounded-lg border border-amber-400/60 bg-amber-50 dark:bg-amber-900/20 text-amber-500 hover:bg-amber-100 dark:hover:bg-amber-900/40 focus:outline-none focus:ring-2 focus:ring-amber-400 disabled:opacity-50"
                                                title="Registrar escaneo"
                                                data-url="{{ route('scanners.list.scan', [$event, $gAssignment]) }}">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </button>
                                        @elseif($gScan)
                                            <div class="inline-flex items-center justify-end gap-1">
                                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-emerald-500/50 bg-emerald-50 dark:bg-emerald-900/25 text-emerald-600 dark:text-emerald-400"
                                                    title="Ya registrado" role="img" aria-label="Ya registrado">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg>
                                                </span>
                                                <button type="button"
                                                    class="js-list-edit-scan inline-flex items-center justify-center w-9 h-9 rounded-lg border border-sky-500/50 bg-sky-50 dark:bg-sky-900/25 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-900/40 focus:outline-none focus:ring-2 focus:ring-sky-400"
                                                    title="Editar escaneo"
                                                    data-scan-id="{{ $gScan->id }}">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-8 text-center text-gray-500 dark:text-gray-400">
                                        No hay registros en el listado para este evento.
                                    </td>
                                </tr>
                            @endforelse
                            @if($listRows->isNotEmpty())
                                <tr id="list-filter-empty-row" class="hidden">
                                    <td colspan="6" class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Ningún registro coincide con la búsqueda.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <x-modal name="list-edit-scan-modal" :show="false" maxWidth="md">
        <div class="p-6 sm:p-8">
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Editar escaneo</h3>
            <p id="list-edit-scan-error" class="hidden text-sm text-red-600 mb-3"></p>
            <form id="list-edit-scan-form" class="space-y-4">
                <input type="hidden" id="list-edit-scan-id" value="">
                <div>
                    <label for="list-edit-value" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Lectura / Valor</label>
                    <input type="text" id="list-edit-value" required
                        class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm text-sm">
                </div>
                <div>
                    <label for="list-edit-id-list" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">ID (listado)</label>
                    <input type="text" id="list-edit-id-list"
                        class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm text-sm">
                </div>
                <div>
                    <label for="list-edit-qr-list" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">QR (listado)</label>
                    <input type="text" id="list-edit-qr-list"
                        class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm text-sm">
                </div>
                <div>
                    <label for="list-edit-observations" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Observaciones</label>
                    <textarea id="list-edit-observations" rows="3"
                        class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm text-sm"></textarea>
                </div>
                <div>
                    <label for="list-edit-scanned-at" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Fecha / hora</label>
                    <input type="datetime-local" id="list-edit-scanned-at"
                        class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm text-sm">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button"
                        class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700"
                        x-on:click="$dispatch('close-modal', 'list-edit-scan-modal')">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-[#2e4f70] text-white hover:bg-[#243d58]">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    @include('scanners.partials.manual-scan-modal', [
        'modalName' => 'list-manual-scan-modal',
        'idPrefix' => 'list-manual',
        'showAssignmentPicker' => true,
        'assignmentWrapperClass' => '',
    ])

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const scanBaseUrl = @json($scanBaseUrl);
                const userTotalsEl = document.getElementById('userTotals');
                const generalTotalsEl = document.getElementById('generalTotals');

                function updateListCountersFromData(data) {
                    if (!data) {
                        return;
                    }
                    if (userTotalsEl != null && data.user_scans != null) {
                        userTotalsEl.textContent = String(data.user_scans);
                    }
                    if (generalTotalsEl != null && data.scans != null && data.total != null) {
                        generalTotalsEl.textContent = data.scans + '/' + data.total;
                    }
                }

                const listSearchInput = document.getElementById('list-grid-search');
                const listFilterEmptyRow = document.getElementById('list-filter-empty-row');
                function applyListGridFilter() {
                    if (!listSearchInput) return;
                    const q = listSearchInput.value.trim().toLowerCase();
                    const rows = document.querySelectorAll('tbody tr.list-grid-data-row');
                    let n = 0;
                    rows.forEach((tr) => {
                        const blob = (tr.getAttribute('data-search') || tr.textContent || '').toLowerCase();
                        const show = !q || blob.includes(q);
                        tr.classList.toggle('hidden', !show);
                        if (show) n++;
                    });
                    if (listFilterEmptyRow) {
                        listFilterEmptyRow.classList.toggle('hidden', !(q && n === 0 && rows.length > 0));
                    }
                }
                listSearchInput?.addEventListener('input', applyListGridFilter);
                listSearchInput?.addEventListener('search', applyListGridFilter);

                function refreshListRowSearchBlob(tr) {
                    if (!tr || !tr.classList.contains('list-grid-data-row')) return;
                    const idCell = tr.querySelector('td:first-of-type');
                    const qr = tr.querySelector('.qr-display-cell');
                    const obs = tr.querySelector('.obs-display-cell');
                    const estado = tr.querySelector('.estado-cell');
                    const lastScan = tr.querySelector('.last-scan-cell');
                    const estText = (estado?.textContent || '').toLowerCase();
                    const estadoToken = estText.includes('pendiente') ? 'pendiente' : 'registrado';
                    const blob = [
                        idCell?.textContent.trim() || '',
                        qr?.textContent.trim() || '',
                        obs?.textContent.trim() || '',
                        estadoToken,
                        lastScan?.textContent.trim() || '',
                    ].join(' ').toLowerCase().replace(/\s+/g, ' ').trim();
                    tr.setAttribute('data-search', blob);
                }
                const checkSvg = '<span class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-emerald-500/50 bg-emerald-50 dark:bg-emerald-900/25 text-emerald-600 dark:text-emerald-400" title="Ya registrado" role="img" aria-label="Ya registrado"><svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg></span>';
                const pencilBtn = (scanId) => '<button type="button" class="js-list-edit-scan inline-flex items-center justify-center w-9 h-9 rounded-lg border border-sky-500/50 bg-sky-50 dark:bg-sky-900/25 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-900/40 focus:outline-none focus:ring-2 focus:ring-sky-400" title="Editar escaneo" data-scan-id="' + scanId + '"><svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg></button>';
                const estadoRegistradoHtml = '<span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">Registrado</span>';

                function registeredActionsHtml(scanId) {
                    return '<div class="inline-flex items-center justify-end gap-1">' + checkSvg + pencilBtn(scanId) + '</div>';
                }

                function formatLastScan(iso) {
                    if (!iso) return '';
                    const parts = iso.split(' ');
                    const date = parts[0];
                    const time = (parts[1] || '').slice(0, 5);
                    const ymd = date.split('-');
                    if (ymd.length === 3) return ymd[2] + '/' + ymd[1] + '/' + ymd[0] + ' ' + time;
                    return iso;
                }

                document.querySelectorAll('.js-list-scan').forEach((btn) => {
                    btn.addEventListener('click', async () => {
                        const url = btn.getAttribute('data-url');
                        if (!url || !csrf) return;
                        btn.disabled = true;
                        try {
                            const res = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf,
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                body: '{}',
                            });
                            const data = await res.json().catch(() => ({}));
                            if (!res.ok) {
                                throw new Error(data.message || 'No se pudo registrar el escaneo.');
                            }
                            const tr = btn.closest('tr');
                            if (tr) {
                                tr.classList.remove(
                                    'bg-white', 'dark:bg-gray-900', 'hover:bg-gray-50', 'dark:hover:bg-gray-800/90',
                                    'border-l-4', 'border-gray-200', 'dark:border-gray-600'
                                );
                                tr.classList.add(
                                    'bg-emerald-200/90', 'dark:bg-emerald-900/50', 'hover:bg-emerald-300/95', 'dark:hover:bg-emerald-800/55',
                                    'border-l-4', 'border-emerald-600', 'dark:border-emerald-400'
                                );
                                const est = tr.querySelector('.estado-cell');
                                if (est) est.innerHTML = estadoRegistradoHtml;
                                const l = tr.querySelector('.last-scan-cell');
                                if (l && data.last_scanned_at) {
                                    l.textContent = formatLastScan(data.last_scanned_at);
                                }
                                const act = tr.querySelector('.actions-cell');
                                if (act && data.scan_id) {
                                    act.innerHTML = registeredActionsHtml(data.scan_id);
                                }
                                refreshListRowSearchBlob(tr);
                                applyListGridFilter();
                                updateListCountersFromData(data);
                            }
                        } catch (e) {
                            alert(e.message || 'Error');
                            btn.disabled = false;
                        }
                    });
                });

                const listManualModalName = 'list-manual-scan-modal';
                const listManualPrefix = 'list-manual';
                const btnListOpenManual = document.getElementById('list-btn-open-manual-scan-modal');
                const listManualForm = document.getElementById(listManualPrefix + '-scan-form');
                const listManualError = document.getElementById(listManualPrefix + '-scan-error');
                const listManualTableAssignmentId = document.getElementById(listManualPrefix + '-table-assignment-id');
                const listManualListSearch = document.getElementById(listManualPrefix + '-list-search');
                const listManualAssignmentResults = document.getElementById(listManualPrefix + '-assignment-results');
                const listManualValueInput = document.getElementById(listManualPrefix + '-value');
                const listManualObservationInput = document.getElementById(listManualPrefix + '-observation');
                let listAssignmentSearchTimer = null;
                const assignmentsSearchUrl = @json(route('scanners.assignments.search'));

                function clearListManualPicker() {
                    if (listManualTableAssignmentId) {
                        listManualTableAssignmentId.value = '';
                    }
                    if (listManualListSearch) {
                        listManualListSearch.value = '';
                    }
                    if (listManualAssignmentResults) {
                        listManualAssignmentResults.innerHTML = '';
                        listManualAssignmentResults.classList.add('hidden');
                    }
                }

                function renderListAssignmentResults(items) {
                    if (!listManualAssignmentResults) {
                        return;
                    }
                    listManualAssignmentResults.innerHTML = '';
                    if (!items.length) {
                        listManualAssignmentResults.classList.add('hidden');
                        return;
                    }
                    listManualAssignmentResults.classList.remove('hidden');
                    items.forEach((row) => {
                        const b = document.createElement('button');
                        b.type = 'button';
                        b.className = 'w-full text-left px-3 py-2 text-sm text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800';
                        b.addEventListener('click', () => {
                            if (listManualTableAssignmentId) {
                                listManualTableAssignmentId.value = String(row.id);
                            }
                            if (listManualValueInput) {
                                listManualValueInput.value = row.guest_name || '';
                            }
                            if (listManualObservationInput) {
                                listManualObservationInput.value = row.observations || '';
                            }
                            listManualAssignmentResults.innerHTML = '';
                            listManualAssignmentResults.classList.add('hidden');
                            if (listManualListSearch) {
                                listManualListSearch.value = '';
                            }
                        });
                        const line = document.createElement('div');
                        line.className = 'text-xs text-gray-500 dark:text-gray-400';
                        line.textContent = 'ID: ' + (row.table_number != null ? String(row.table_number) : '?');
                        const qrLine = document.createElement('div');
                        qrLine.className = 'font-medium';
                        qrLine.textContent = row.guest_name || '';
                        b.appendChild(line);
                        b.appendChild(qrLine);
                        listManualAssignmentResults.appendChild(b);
                    });
                }

                async function runListAssignmentsSearch() {
                    if (!listManualListSearch) {
                        return;
                    }
                    const q = listManualListSearch.value.trim();
                    try {
                        const url = assignmentsSearchUrl + '?q=' + encodeURIComponent(q);
                        const res = await fetch(url, {
                            headers: { 'Accept': 'application/json' },
                        });
                        const json = await res.json().catch(() => ({}));
                        renderListAssignmentResults(Array.isArray(json.data) ? json.data : []);
                    } catch (e) {
                        renderListAssignmentResults([]);
                    }
                }

                btnListOpenManual?.addEventListener('click', () => {
                    clearListManualPicker();
                    if (listManualForm) {
                        listManualForm.reset();
                    }
                    if (listManualError) {
                        listManualError.classList.add('hidden');
                        listManualError.textContent = '';
                    }
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: listManualModalName }));
                });

                listManualListSearch?.addEventListener('input', () => {
                    clearTimeout(listAssignmentSearchTimer);
                    listAssignmentSearchTimer = setTimeout(runListAssignmentsSearch, 300);
                });

                listManualValueInput?.addEventListener('input', () => {
                    if (listManualTableAssignmentId && listManualTableAssignmentId.value) {
                        listManualTableAssignmentId.value = '';
                    }
                });

                listManualForm?.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    if (!csrf) {
                        return;
                    }
                    if (listManualError) {
                        listManualError.classList.add('hidden');
                        listManualError.textContent = '';
                    }
                    const formData = new FormData(listManualForm);
                    const payload = {
                        value: formData.get('value'),
                        observation: formData.get('observation'),
                    };
                    if (listManualTableAssignmentId && listManualTableAssignmentId.value) {
                        const tid = parseInt(listManualTableAssignmentId.value, 10);
                        if (!Number.isNaN(tid)) {
                            payload.table_assignment_id = tid;
                        }
                    }
                    try {
                        const response = await fetch(@json(route('scanners.manual')), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                            },
                            body: JSON.stringify(payload),
                        });
                        const data = await response.json().catch(() => ({}));
                        if (!response.ok) {
                            throw new Error(data.message || 'No se pudo guardar el scan manual.');
                        }
                        listManualForm.reset();
                        clearListManualPicker();
                        window.dispatchEvent(new CustomEvent('close-modal', { detail: listManualModalName }));
                        window.location.reload();
                    } catch (error) {
                        if (listManualError) {
                            listManualError.textContent = error.message || 'No se pudo guardar el scan manual.';
                            listManualError.classList.remove('hidden');
                        }
                    }
                });

                const errEl = document.getElementById('list-edit-scan-error');
                const form = document.getElementById('list-edit-scan-form');

                document.querySelector('table tbody')?.addEventListener('click', async (e) => {
                    const editBtn = e.target.closest('.js-list-edit-scan');
                    if (!editBtn || !csrf) return;
                    const scanId = editBtn.getAttribute('data-scan-id');
                    if (!scanId) return;
                    errEl.classList.add('hidden');
                    errEl.textContent = '';
                    try {
                        const res = await fetch(scanBaseUrl + '/' + scanId + '/edit-data', {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) {
                            throw new Error(data.message || 'No se pudo cargar el escaneo.');
                        }
                        const s = data.scan;
                        document.getElementById('list-edit-scan-id').value = String(s.id);
                        document.getElementById('list-edit-value').value = s.value || '';
                        document.getElementById('list-edit-id-list').value = s.id_list || '';
                        document.getElementById('list-edit-qr-list').value = s.qr_list || '';
                        document.getElementById('list-edit-observations').value = s.observations || '';
                        document.getElementById('list-edit-scanned-at').value = s.scanned_at || '';
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'list-edit-scan-modal' }));
                    } catch (err) {
                        alert(err.message || 'Error');
                    }
                });

                form?.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    errEl.classList.add('hidden');
                    errEl.textContent = '';
                    const scanId = document.getElementById('list-edit-scan-id').value;
                    if (!scanId || !csrf) return;
                    const payload = {
                        value: document.getElementById('list-edit-value').value,
                        id_list: document.getElementById('list-edit-id-list').value,
                        qr_list: document.getElementById('list-edit-qr-list').value,
                        observations: document.getElementById('list-edit-observations').value,
                        scanned_at: document.getElementById('list-edit-scanned-at').value || null,
                    };
                    try {
                        const res = await fetch(scanBaseUrl + '/' + scanId, {
                            method: 'PUT',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify(payload),
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) {
                            const msg = data.message || (data.errors && Object.values(data.errors).flat().join(' ')) || 'No se pudo guardar.';
                            throw new Error(msg);
                        }
                        const tr = document.querySelector('.js-list-edit-scan[data-scan-id="' + scanId + '"]')?.closest('tr');
                        if (tr && data.scan) {
                            const qr = tr.querySelector('.qr-display-cell');
                            if (qr) qr.textContent = data.scan.value || '';
                            const obs = tr.querySelector('.obs-display-cell');
                            if (obs) obs.textContent = data.scan.observations || '';
                            const l = tr.querySelector('.last-scan-cell');
                            if (l && data.scan.scanned_at) {
                                l.textContent = formatLastScan(data.scan.scanned_at);
                            }
                            refreshListRowSearchBlob(tr);
                            applyListGridFilter();
                        }
                        window.dispatchEvent(new CustomEvent('close-modal', { detail: 'list-edit-scan-modal' }));
                    } catch (err) {
                        errEl.textContent = err.message || 'Error';
                        errEl.classList.remove('hidden');
                    }
                });
            });
        </script>
    @endpush

    <div class="fixed bottom-6 right-6 z-50">
        <div id="userTotals"
            class="w-16 h-16 rounded-full
               bg-[#f8af20] text-gray-900
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
               bg-[#2e4f70] text-white
               flex items-center justify-center
               text-base font-bold
               shadow-xl
               border-2 border-white dark:border-gray-800
               select-none">
            {{ $scans ?? 0 }}/{{ $total ?? 0 }}
        </div>
    </div>
</x-app-layout>

