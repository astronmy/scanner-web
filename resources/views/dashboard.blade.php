<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Seleccionar evento
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($events as $event)
                    <div class="w-full h-full min-h-[200px] flex flex-col rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg hover:shadow-xl overflow-hidden transition-all duration-200">
                        <div class="aspect-[16/10] w-full shrink-0 bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            @if($event->cover_image)
                                <img src="{{ $event->coverImageUrl() }}" alt="" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-emerald-600/20 dark:bg-emerald-500/20">
                                    <span class="text-4xl font-bold text-emerald-600 dark:text-emerald-400 opacity-60">E</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 flex flex-col items-center justify-center p-4">
                            <span class="text-lg font-bold text-gray-800 dark:text-gray-100 text-center leading-tight">
                                {{ $event->name }}
                            </span>

                            <div class="mt-4 flex items-center gap-2">
                                <a href="{{ route('dashboard.event.scanner', $event->event_id ?? $event->id) }}"
                                   class="inline-flex items-center px-3 py-2 rounded-md bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                                   title="Iniciar escáner">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h4.5v4.5h-4.5V4.5zM15.75 4.5h4.5v4.5h-4.5V4.5zM3.75 15.75h4.5v4.5h-4.5v-4.5zM13.5 13.5h1.875v1.875H13.5V13.5zM17.25 13.5h1.875v1.875H17.25V13.5zM13.5 17.25h1.875v1.875H13.5V17.25zM17.25 17.25h1.875v1.875H17.25V17.25z" />
                                    </svg>
                                </a>
                                <button type="button"
                                    class="js-open-dashboard-manual-scan inline-flex items-center px-3 py-2 rounded-md bg-slate-600 text-white text-sm font-semibold hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 disabled:opacity-50"
                                    title="Cargar scan manual"
                                    data-event-id="{{ $event->event_id ?? $event->id }}">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </button>
                                <a href="{{ route('dashboard.event', $event->event_id ?? $event->id) }}"
                                   class="inline-flex items-center px-3 py-2 rounded-md bg-[#406075] text-white text-sm font-semibold hover:bg-[#355566] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#406075]"
                                   title="Opciones del evento">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317a1.724 1.724 0 013.35 0l.17.696a1.724 1.724 0 002.573 1.066l.61-.356a1.724 1.724 0 012.35.63 1.724 1.724 0 010 1.724l-.355.61a1.724 1.724 0 001.065 2.573l.697.17a1.724 1.724 0 010 3.35l-.697.17a1.724 1.724 0 00-1.065 2.573l.356.61a1.724 1.724 0 01-.63 2.35 1.724 1.724 0 01-1.724 0l-.61-.356a1.724 1.724 0 00-2.573 1.065l-.17.697a1.724 1.724 0 01-3.35 0l-.17-.697a1.724 1.724 0 00-2.573-1.065l-.61.356a1.724 1.724 0 01-2.35-.63 1.724 1.724 0 010-1.724l.355-.61A1.724 1.724 0 014.317 13.7l-.697-.17a1.724 1.724 0 010-3.35l.697-.17A1.724 1.724 0 005.382 7.44l-.356-.61a1.724 1.724 0 01.63-2.35 1.724 1.724 0 011.724 0l.61.356a1.724 1.724 0 002.573-1.065l.17-.697z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            @if($events->hasPages())
                <div class="mt-6">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>

    @include('scanners.partials.manual-scan-modal', [
        'modalName' => 'dashboard-manual-scan-modal',
        'idPrefix' => 'db-manual',
        'showAssignmentPicker' => true,
        'assignmentWrapperClass' => 'hidden',
    ])

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalName = 'dashboard-manual-scan-modal';
                const idPrefix = 'db-manual';
                let dashboardIsStorageType = false;
                let assignmentSearchTimer = null;

                const manualScanForm = document.getElementById(idPrefix + '-scan-form');
                const manualScanError = document.getElementById(idPrefix + '-scan-error');
                const manualTableAssignmentId = document.getElementById(idPrefix + '-table-assignment-id');
                const manualListSearch = document.getElementById(idPrefix + '-list-search');
                const manualAssignmentResults = document.getElementById(idPrefix + '-assignment-results');
                const manualValueInput = document.getElementById(idPrefix + '-value');
                const manualObservationInput = document.getElementById(idPrefix + '-observation');
                const assignmentSection = document.getElementById(idPrefix + '-assignment-section');
                const assignmentsSearchUrl = @json(route('scanners.assignments.search'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const dashboardBaseUrl = @json(url('/dashboard'));

                function dashboardContextUrl(eventId) {
                    return dashboardBaseUrl + '/' + eventId + '/context';
                }

                function clearDashboardManualPicker() {
                    if (manualTableAssignmentId) {
                        manualTableAssignmentId.value = '';
                    }
                    if (manualListSearch) {
                        manualListSearch.value = '';
                    }
                    if (manualAssignmentResults) {
                        manualAssignmentResults.innerHTML = '';
                        manualAssignmentResults.classList.add('hidden');
                    }
                }

                function renderAssignmentResults(items) {
                    if (!manualAssignmentResults) {
                        return;
                    }
                    manualAssignmentResults.innerHTML = '';
                    if (!items.length) {
                        manualAssignmentResults.classList.add('hidden');
                        return;
                    }
                    manualAssignmentResults.classList.remove('hidden');
                    items.forEach((row) => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'w-full text-left px-3 py-2 text-sm text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-800';
                        btn.addEventListener('click', () => {
                            if (manualTableAssignmentId) {
                                manualTableAssignmentId.value = String(row.id);
                            }
                            if (manualValueInput) {
                                manualValueInput.value = row.guest_name || '';
                            }
                            if (manualObservationInput) {
                                manualObservationInput.value = row.observations || '';
                            }
                            manualAssignmentResults.innerHTML = '';
                            manualAssignmentResults.classList.add('hidden');
                            if (manualListSearch) {
                                manualListSearch.value = '';
                            }
                        });
                        const line = document.createElement('div');
                        line.className = 'text-xs text-gray-500 dark:text-gray-400';
                        line.textContent = 'ID: ' + (row.table_number != null ? String(row.table_number) : '?');
                        const qrLine = document.createElement('div');
                        qrLine.className = 'font-medium';
                        qrLine.textContent = row.guest_name || '';
                        btn.appendChild(line);
                        btn.appendChild(qrLine);
                        manualAssignmentResults.appendChild(btn);
                    });
                }

                async function runAssignmentsSearch() {
                    if (dashboardIsStorageType || !manualListSearch) {
                        return;
                    }
                    const q = manualListSearch.value.trim();
                    try {
                        const url = assignmentsSearchUrl + '?q=' + encodeURIComponent(q);
                        const res = await fetch(url, {
                            headers: { 'Accept': 'application/json' },
                        });
                        const json = await res.json().catch(() => ({}));
                        renderAssignmentResults(Array.isArray(json.data) ? json.data : []);
                    } catch (e) {
                        renderAssignmentResults([]);
                    }
                }

                document.querySelectorAll('.js-open-dashboard-manual-scan').forEach((btn) => {
                    btn.addEventListener('click', async () => {
                        const eventId = btn.getAttribute('data-event-id');
                        if (!eventId) {
                            return;
                        }
                        btn.disabled = true;
                        try {
                            const res = await fetch(dashboardContextUrl(eventId), {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                body: JSON.stringify({}),
                            });
                            const data = await res.json().catch(() => ({}));
                            if (!res.ok) {
                                throw new Error(data.message || 'No se pudo activar el evento.');
                            }
                            dashboardIsStorageType = data.is_storage_type === true;
                            if (assignmentSection) {
                                if (dashboardIsStorageType) {
                                    assignmentSection.classList.add('hidden');
                                } else {
                                    assignmentSection.classList.remove('hidden');
                                }
                            }
                            if (!dashboardIsStorageType) {
                                clearDashboardManualPicker();
                            }
                            if (manualScanForm) {
                                manualScanForm.reset();
                            }
                            if (manualScanError) {
                                manualScanError.classList.add('hidden');
                                manualScanError.textContent = '';
                            }
                            window.dispatchEvent(new CustomEvent('open-modal', { detail: modalName }));
                        } catch (e) {
                            alert(e.message || 'No se pudo abrir la carga manual.');
                        } finally {
                            btn.disabled = false;
                        }
                    });
                });

                if (manualListSearch) {
                    manualListSearch.addEventListener('input', () => {
                        clearTimeout(assignmentSearchTimer);
                        assignmentSearchTimer = setTimeout(runAssignmentsSearch, 300);
                    });
                }

                if (manualValueInput) {
                    manualValueInput.addEventListener('input', () => {
                        if (manualTableAssignmentId && manualTableAssignmentId.value) {
                            manualTableAssignmentId.value = '';
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
                        if (!dashboardIsStorageType && manualTableAssignmentId && manualTableAssignmentId.value) {
                            const tid = parseInt(manualTableAssignmentId.value, 10);
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
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                                body: JSON.stringify(payload),
                            });

                            const data = await response.json().catch(() => ({}));
                            if (!response.ok) {
                                throw new Error(data.message || 'No se pudo guardar el scan manual.');
                            }

                            manualScanForm.reset();
                            clearDashboardManualPicker();
                            window.dispatchEvent(new CustomEvent('close-modal', { detail: modalName }));
                        } catch (error) {
                            if (manualScanError) {
                                manualScanError.textContent = error.message || 'No se pudo guardar el scan manual.';
                                manualScanError.classList.remove('hidden');
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
