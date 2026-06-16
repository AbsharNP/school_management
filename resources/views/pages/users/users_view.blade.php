@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Users</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage system users and their access</p>
                    </div>
                    <button id="btnCreate"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 text-white rounded-lg hover:bg-brand-600 transition-all duration-200 shadow-sm hover:shadow-md font-medium text-sm">
                        <i class="fas fa-plus text-xs"></i>
                        Add User
                    </button>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Class Group</th>
                                <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($users as $index => $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors" data-id="{{ $row->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">{{ $row->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $row->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($row->role)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-500/10 dark:text-purple-400">
                                                {{ $row->role->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($row->classGroup)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400">
                                                {{ $row->classGroup->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button"
                                                class="btn-edit inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20 transition-colors"
                                                title="Edit user" data-id="{{ $row->id }}">
                                                <i class="fas fa-pen-to-square text-sm"></i>
                                            </button>
                                            <button type="button"
                                                class="btn-delete inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20 transition-colors"
                                                title="Delete user" data-id="{{ $row->id }}">
                                                <i class="fas fa-trash-can text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-users text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">No users found</p>
                                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Click "Add User" to create your first user</p>
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
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 dark:text-white">Add User</h3>
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
                                placeholder="Full name">
                            <p id="nameError" class="mt-1 text-xs text-red-500 hidden"></p>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email"
                                class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:bg-gray-900 transition-colors"
                                placeholder="user@email.com">
                            <p id="emailError" class="mt-1 text-xs text-red-500 hidden"></p>
                        </div>
                        <div id="passwordField">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Password <span id="passwordRequired" class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password" name="password"
                                class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:bg-gray-900 transition-colors"
                                placeholder="Min 8 characters">
                            <p id="passwordError" class="mt-1 text-xs text-red-500 hidden"></p>
                            <p id="passwordHint" class="mt-1 text-xs text-gray-400 hidden">Leave blank to keep current password</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="role_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    Role
                                </label>
                                <select id="role_id" name="role_id"
                                    class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-transparent px-4 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:bg-gray-900 transition-colors">
                                    <option value="">Select role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <p id="role_idError" class="mt-1 text-xs text-red-500 hidden"></p>
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
            const BASE_URL = '/users';

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

            function openModal(title = 'Add User', isEdit = false) {
                $('#modalTitle').text(title);
                if (isEdit) {
                    $('#passwordRequired').addClass('hidden');
                    $('#passwordHint').removeClass('hidden');
                    $('#password').attr('placeholder', 'Leave blank to keep current');
                } else {
                    $('#passwordRequired').removeClass('hidden');
                    $('#passwordHint').addClass('hidden');
                    $('#password').attr('placeholder', 'Min 8 characters');
                }
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
                openModal('Add User', false);
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
                            $('#password').val('');
                            $('#role_id').val(d.role_id);
                            $('#class_group_id').val(d.class_group_id);
                            openModal('Edit User', true);
                        }
                    },
                    error: function () {
                        showToast('Failed to load user data.', 'error');
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

                const formData = {
                    _token: CSRF_TOKEN,
                    name: $('#name').val(),
                    email: $('#email').val(),
                    role_id: $('#role_id').val(),
                    class_group_id: $('#class_group_id').val(),
                };

                const password = $('#password').val();
                if (password) {
                    formData.password = password;
                }

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
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
                if (confirm('Are you sure you want to delete this user?')) {
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
                                                <td colspan="6" class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center">
                                                        <i class="fas fa-users text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                                        <p class="text-gray-500 dark:text-gray-400 font-medium">No users found</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        `);
                                    }
                                });
                            }
                        },
                        error: function () {
                            showToast('Failed to delete user.', 'error');
                        }
                    });
                }
            });
        });
    </script>
@endpush