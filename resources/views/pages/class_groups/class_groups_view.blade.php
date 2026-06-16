@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Class Groups</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage class groups and sections</p>
                    </div>
                    <button id="btnCreate"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 text-white rounded-lg hover:bg-brand-600 transition-all duration-200 shadow-sm hover:shadow-md font-medium text-sm">
                        <i class="fas fa-plus text-xs"></i>
                        Add Class Group
                    </button>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Head Teacher</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Classes</th>
                                <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($classGroups as $index => $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors" data-id="{{ $row->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">{{ $row->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        {{ $row->headTeacher->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @if($row->standards->count())
                                            {{ $row->standards->pluck('name')->join(', ') }}
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button"
                                                class="btn-edit inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20 transition-colors"
                                                title="Edit class group" data-id="{{ $row->id }}">
                                                <i class="fas fa-pen-to-square text-sm"></i>
                                            </button>
                                            <button type="button"
                                                class="btn-delete inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20 transition-colors"
                                                title="Delete class group" data-id="{{ $row->id }}">
                                                <i class="fas fa-trash-can text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-layer-group text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">No class groups found</p>
                                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Click "Add Class Group" to create your first group</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Toast -->
        <div id="toast" class="hidden fixed top-5 right-5 px-5 py-3 rounded-xl shadow-lg z-50 transition-all duration-300 transform translate-x-full opacity-0">
            <div class="flex items-center gap-2">
                <i id="toastIcon" class="fas"></i>
                <span id="toastMessage" class="text-sm font-medium"></span>
            </div>
        </div>

        <!-- Modal Backdrop -->
        <div id="modalBackdrop" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[99998] transition-opacity duration-300"></div>

        <!-- Modal -->
        <div id="formModal" class="hidden fixed inset-0 z-[99999] flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 dark:text-white">Add Class Group</h3>
                    <button id="btnCloseModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <i class="fas fa-xmark text-xl"></i>
                    </button>
                </div>
                <form id="crudForm">
                    <input type="hidden" id="recordId" value="">
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name"
                                class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:bg-gray-900 transition-colors"
                                placeholder="e.g. Primary Group">
                            <p id="nameError" class="mt-1 text-xs text-red-500 hidden"></p>
                        </div>
                        <div>
                            <label for="head_teacher_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Head Teacher
                            </label>
                            <select id="head_teacher_id" name="head_teacher_id"
                                class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:bg-gray-900 transition-colors">
                                <option value="">No head teacher</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            <p id="head_teacher_idError" class="mt-1 text-xs text-red-500 hidden"></p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" id="btnCancel"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="btnSubmit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-brand-500 rounded-lg hover:bg-brand-600 transition-colors shadow-sm">
                            <span id="btnSubmitText">Save</span>
                            <i id="btnSubmitSpinner" class="fas fa-spinner fa-spin ml-2 hidden"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const BASE_URL = '/class-groups';

            function showToast(message, type = 'success') {
                const toast = $('#toast');
                const icon = $('#toastIcon');
                const msg = $('#toastMessage');
                toast.removeClass('hidden bg-green-500 bg-red-500 translate-x-full opacity-0');
                if (type === 'success') {
                    toast.addClass('bg-green-500 text-white');
                    icon.attr('class', 'fas fa-check-circle');
                } else {
                    toast.addClass('bg-red-500 text-white');
                    icon.attr('class', 'fas fa-exclamation-circle');
                }
                msg.text(message);
                setTimeout(() => toast.removeClass('translate-x-full opacity-0'), 10);
                setTimeout(() => {
                    toast.addClass('translate-x-full opacity-0');
                    setTimeout(() => toast.addClass('hidden'), 300);
                }, 3000);
            }

            function openModal(title = 'Add Class Group') {
                $('#modalTitle').text(title);
                $('#formModal, #modalBackdrop').removeClass('hidden');
                setTimeout(() => $('#modalContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'), 10);
            }

            function closeModal() {
                $('#modalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
                setTimeout(() => {
                    $('#formModal, #modalBackdrop').addClass('hidden');
                    resetForm();
                }, 200);
            }

            function resetForm() {
                $('#crudForm')[0].reset();
                $('#recordId').val('');
                clearErrors();
            }

            function clearErrors() {
                $('.text-red-500.text-xs').addClass('hidden').text('');
            }

            function showErrors(errors) {
                clearErrors();
                $.each(errors, function (field, messages) {
                    $('#' + field + 'Error').removeClass('hidden').text(messages[0]);
                });
            }

            $('#btnCreate').on('click', function () {
                resetForm();
                openModal('Add Class Group');
            });

            $('#btnCloseModal, #btnCancel, #modalBackdrop').on('click', closeModal);

            $(document).on('click', '.btn-edit', function () {
                const id = $(this).data('id');
                $.ajax({
                    url: BASE_URL + '/' + id,
                    type: 'GET',
                    success: function (res) {
                        if (res.success) {
                            $('#recordId').val(res.data.id);
                            $('#name').val(res.data.name);
                            $('#head_teacher_id').val(res.data.head_teacher_id);
                            openModal('Edit Class Group');
                        }
                    },
                    error: function () {
                        showToast('Failed to load data.', 'error');
                    }
                });
            });

            $('#crudForm').on('submit', function (e) {
                e.preventDefault();
                clearErrors();
                const id = $('#recordId').val();
                const isEdit = id !== '';
                const url = isEdit ? BASE_URL + '/' + id : BASE_URL;
                const method = isEdit ? 'PUT' : 'POST';

                $('#btnSubmitSpinner').removeClass('hidden');
                $('#btnSubmitText').text(isEdit ? 'Updating...' : 'Saving...');

                $.ajax({
                    url: url,
                    type: method,
                    data: {
                        _token: CSRF_TOKEN,
                        name: $('#name').val(),
                        head_teacher_id: $('#head_teacher_id').val(),
                    },
                    success: function (res) {
                        if (res.success) {
                            showToast(res.message);
                            closeModal();
                            setTimeout(() => location.reload(), 500);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            showErrors(xhr.responseJSON.errors);
                        } else {
                            showToast('An error occurred. Please try again.', 'error');
                        }
                    },
                    complete: function () {
                        $('#btnSubmitSpinner').addClass('hidden');
                        $('#btnSubmitText').text('Save');
                    }
                });
            });

            $(document).on('click', '.btn-delete', function () {
                const id = $(this).data('id');
                const row = $(this).closest('tr');
                if (confirm('Are you sure you want to delete this class group?')) {
                    $.ajax({
                        url: BASE_URL + '/' + id,
                        type: 'DELETE',
                        data: { _token: CSRF_TOKEN },
                        success: function (res) {
                            if (res.success) {
                                showToast(res.message);
                                row.fadeOut(300, function () {
                                    $(this).remove();
                                    if ($('#tableBody tr').length === 0) {
                                        $('#tableBody').html(`
                                            <tr id="emptyRow">
                                                <td colspan="5" class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center">
                                                        <i class="fas fa-layer-group text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                                        <p class="text-gray-500 dark:text-gray-400 font-medium">No class groups found</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        `);
                                    }
                                });
                            }
                        },
                        error: function () {
                            showToast('Failed to delete class group.', 'error');
                        }
                    });
                }
            });
        });
    </script>
@endpush
