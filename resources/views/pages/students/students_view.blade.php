@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Students</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage student records and information</p>
                    </div>
                    <form method="GET" action="{{ route('students.index') }}" class="flex items-center gap-2">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-search text-sm"></i>
                            </span>
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Search by name, admission no. or email"
                                class="w-full sm:w-80 h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-transparent pl-10 pr-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:bg-gray-900 transition-colors">
                        </div>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 text-white rounded-lg hover:bg-brand-600 transition-all duration-200 shadow-sm hover:shadow-md font-medium text-sm">
                            Search
                        </button>
                        @if($search !== '')
                            <a href="{{ route('students.index') }}"
                                class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Admission No.</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Class Group</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Class</th>
                                <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($students as $index => $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors" data-id="{{ $row->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">{{ $row->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $row->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $row->admission_no ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($row->classGroup)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400">
                                                {{ $row->classGroup->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($row->standard)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-400">
                                                {{ $row->standard->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @can('update', $row)
                                            <button type="button"
                                                class="btn-edit inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20 transition-colors"
                                                title="Edit student" data-id="{{ $row->id }}">
                                                <i class="fas fa-pen-to-square text-sm"></i>
                                            </button>
                                            @endcan
                                            @can('delete', $row)
                                            <button type="button"
                                                class="btn-delete inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20 transition-colors"
                                                title="Delete student" data-id="{{ $row->id }}">
                                                <i class="fas fa-trash-can text-sm"></i>
                                            </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-user-graduate text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">No students found</p>
                                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Click "Add Student" to register your first student</p>
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
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] overflow-y-auto" id="modalContent">
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800 rounded-t-2xl">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 dark:text-white">Add Student</h3>
                    <button id="btnCloseModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <i class="fas fa-xmark text-xl"></i>
                    </button>
                </div>
                <form id="crudForm">
                    <input type="hidden" id="recordId" value="">
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name"
                                    class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:bg-gray-900 transition-colors"
                                    placeholder="Student name">
                                <p id="nameError" class="mt-1 text-xs text-red-500 hidden"></p>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email"
                                    class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:bg-gray-900 transition-colors"
                                    placeholder="student@email.com">
                                <p id="emailError" class="mt-1 text-xs text-red-500 hidden"></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label for="admission_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    Admission No. <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="admission_no" name="admission_no"
                                    class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:bg-gray-900 transition-colors"
                                    placeholder="e.g. ADM001">
                                <p id="admission_noError" class="mt-1 text-xs text-red-500 hidden"></p>
                            </div>
                            <div>
                                <label for="class_group_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    Class Group
                                </label>
                                <select id="class_group_id" name="class_group_id"
                                    class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:bg-gray-900 transition-colors">
                                    <option value="">Select class group</option>
                                    @foreach ($classGroups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                                <p id="class_group_idError" class="mt-1 text-xs text-red-500 hidden"></p>
                            </div>
                            <div>
                                <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    Class
                                </label>
                                <select id="class_id" name="class_id"
                                    class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:bg-gray-900 transition-colors">
                                    <option value="">Select class</option>
                                    @foreach ($standards as $standard)
                                        <option value="{{ $standard->id }}">{{ $standard->name }}</option>
                                    @endforeach
                                </select>
                                <p id="class_idError" class="mt-1 text-xs text-red-500 hidden"></p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700 sticky bottom-0 bg-white dark:bg-gray-800 rounded-b-2xl">
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
            const BASE_URL = '/students';

            function showToast(message, type = 'success') {
                const toast = $('#toast');
                toast.removeClass('hidden bg-green-500 bg-red-500 translate-x-full opacity-0');
                toast.addClass(type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white');
                $('#toastIcon').attr('class', type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle');
                $('#toastMessage').text(message);
                setTimeout(() => toast.removeClass('translate-x-full opacity-0'), 10);
                setTimeout(() => {
                    toast.addClass('translate-x-full opacity-0');
                    setTimeout(() => toast.addClass('hidden'), 300);
                }, 3000);
            }

            function openModal(title = 'Add Student') {
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
                $('[id$="Error"]').addClass('hidden').text('');
            }

            function showErrors(errors) {
                clearErrors();
                $.each(errors, function (field, messages) {
                    $('#' + field + 'Error').removeClass('hidden').text(messages[0]);
                });
            }

            $('#btnCreate').on('click', function () {
                resetForm();
                openModal('Add Student');
            });

            $('#btnCloseModal, #btnCancel, #modalBackdrop').on('click', closeModal);

            $(document).on('click', '.btn-edit', function () {
                const id = $(this).data('id');
                $.ajax({
                    url: BASE_URL + '/' + id,
                    type: 'GET',
                    success: function (res) {
                        if (res.success) {
                            const d = res.data;
                            $('#recordId').val(d.id);
                            $('#name').val(d.name);
                            $('#email').val(d.email);
                            $('#admission_no').val(d.admission_no);
                            $('#class_group_id').val(d.class_group_id);
                            $('#class_id').val(d.class_id);
                            openModal('Edit Student');
                        }
                    },
                    error: function () {
                        showToast('Failed to load student data.', 'error');
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
                        email: $('#email').val(),
                        admission_no: $('#admission_no').val(),
                        class_group_id: $('#class_group_id').val(),
                        class_id: $('#class_id').val(),
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
                if (confirm('Are you sure you want to delete this student?')) {
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
                                                <td colspan="7" class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center">
                                                        <i class="fas fa-user-graduate text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                                        <p class="text-gray-500 dark:text-gray-400 font-medium">No students found</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        `);
                                    }
                                });
                            }
                        },
                        error: function () {
                            showToast('Failed to delete student.', 'error');
                        }
                    });
                }
            });
        });
    </script>
@endpush
