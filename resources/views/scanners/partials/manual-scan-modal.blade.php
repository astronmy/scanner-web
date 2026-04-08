@props([
    'modalName',
    'idPrefix',
    'showAssignmentPicker' => true,
    'assignmentWrapperClass' => '',
])

<x-modal :name="$modalName" :show="false" maxWidth="md">
    <div class="p-6 sm:p-8">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Cargar scan manual</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            Complete el QR/valor y una observación opcional.
        </p>
        <form id="{{ $idPrefix }}-scan-form" class="space-y-4">
            @if($showAssignmentPicker)
                <input type="hidden" name="table_assignment_id" id="{{ $idPrefix }}-table-assignment-id" value="">
                <div id="{{ $idPrefix }}-assignment-section" class="rounded-lg border border-gray-200 dark:border-gray-600 p-3 bg-gray-50 dark:bg-gray-900/40 {{ $assignmentWrapperClass }}">
                    <label for="{{ $idPrefix }}-list-search" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Buscar en listado
                    </label>
                    <input type="text"
                           id="{{ $idPrefix }}-list-search"
                           autocomplete="off"
                           placeholder="ID o QR del invitado..."
                           class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <div id="{{ $idPrefix }}-assignment-results"
                         class="mt-2 max-h-48 overflow-y-auto rounded-md border border-gray-200 dark:border-gray-600 divide-y divide-gray-200 dark:divide-gray-600 hidden empty:hidden"></div>
                    <p id="{{ $idPrefix }}-assignment-search-hint" class="mt-1 text-xs text-gray-500 dark:text-gray-400">Escriba para buscar; elija un registro para completar QR y datos del listado.</p>
                </div>
            @endif
            <div>
                <label for="{{ $idPrefix }}-value" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    QR / Valor
                </label>
                <input id="{{ $idPrefix }}-value"
                       name="value"
                       type="text"
                       class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
            <div>
                <label for="{{ $idPrefix }}-observation" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Observación (opcional)
                </label>
                <textarea id="{{ $idPrefix }}-observation"
                          name="observation"
                          rows="3"
                          class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
            </div>
            <p id="{{ $idPrefix }}-scan-error" class="hidden text-xs text-red-600"></p>
            <div class="flex justify-end gap-3">
                <button type="button"
                    class="inline-flex items-center px-5 py-2.5 text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400"
                    x-on:click="$dispatch('close-modal', '{{ $modalName }}')">
                    Cancelar
                </button>
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 text-base font-medium text-white bg-[#2e4f70] hover:bg-[#243d58] rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2e4f70]">
                    Guardar manual
                </button>
            </div>
        </form>
    </div>
</x-modal>
