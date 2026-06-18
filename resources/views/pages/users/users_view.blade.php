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
                    @can('user-create')
                    <button id="btnCreate"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 text-white rounded-lg hover:bg-brand-600 transition-all duration-200 shadow-sm hover:shadow-md font-medium text-sm">
                        <i class="fas fa-plus text-xs"></i>
                        Add User
                    </button>
                    @endcan
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Details</th>
                                <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($users as $index => $row)
                                @php
                                    $userType = $row->student ? 'student' : ($row->teacher ? 'teacher' : 'other');
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors" data-id="{{ $row->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">{{ $row->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $row->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($userType === 'student')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400">
                                                <i class="fas fa-user-graduate text-[10px]"></i> Student
                                            </span>
                                        @elseif($userType === 'teacher')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-400">
                                                <i class="fas fa-chalkboard-teacher text-[10px]"></i> Teacher
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-500/10 dark:text-purple-400">
                                                <i class="fas fa-user-tie text-[10px]"></i> Employee
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($userType === 'student')
                                            <div class="flex flex-wrap items-center gap-1.5">
                                                <span class="text-xs text-gray-500 dark:text-gray-400 font-mono">#{{ $row->student->admission_no }}</span>
                                                @if($row->student->classGroup)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">{{ $row->student->classGroup->name }}</span>
                                                @endif
                                            </div>
                                        @elseif($userType === 'teacher')
                                            <div class="flex flex-wrap items-center gap-1.5">
                                                @if($row->teacher->subject)
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $row->teacher->subject }}</span>
                                                @endif
                                                @if($row->teacher->classGroup)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400">{{ $row->teacher->classGroup->name }}</span>
                                                @endif
                                            </div>
                                        @else
                                            @if($row->roles->first())
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400">{{ $row->roles->first()->name }}</span>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @can('user-edit')
                                            <button type="button"
                                                class="btn-edit inline-flex items-center justify-center w-9 h-9 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20 transition-colors"
                                                title="Edit user" data-id="{{ $row->id }}">
                                                <i class="fas fa-pen-to-square text-sm"></i>
                                            </button>
                                            @endcan
                                            @can('user-delete')
                                            <button type="button"
                                                class="btn-delete inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20 transition-colors"
                                                title="Delete user" data-id="{{ $row->id }}">
                                                <i class="fas fa-trash-can text-sm"></i>
                                            </button>
                                            @endcan
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
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-xl transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] overflow-y-auto" id="modalContent">
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800 rounded-t-2xl z-10">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-800 dark:text-white">Add User</h3>
                    <button id="btnCloseModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <i class="fas fa-xmark text-xl"></i>
                    </button>
                </div>

                <form id="crudForm">
                    <input type="hidden" id="recordId" value="">
                    <input type="hidden" id="userType" value="other">

                    <div class="p-6 space-y-5">

                        {{-- User type selector --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                User Type <span class="text-red-500">*</span>
                            </label>
                            <div id="typeSelectorWrap" class="grid grid-cols-3 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="user_type_radio" value="student" class="peer sr-only">
                                    <div class="border-2 rounded-xl p-3 text-center transition-all duration-200
                                        border-gray-200 dark:border-gray-600 text-gray-400 dark:text-gray-500
                                        peer-checked:border-blue-500 peer-checked:text-blue-600 peer-checked:bg-blue-50
                                        dark:peer-checked:border-blue-400 dark:peer-checked:text-blue-400 dark:peer-checked:bg-blue-500/10
                                        hover:border-gray-300 dark:hover:border-gray-500">
                                        <i class="fas fa-user-graduate text-xl mb-1 block"></i>
                                        <span class="text-xs font-semibold">Student</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="user_type_radio" value="teacher" class="peer sr-only">
                                    <div class="border-2 rounded-xl p-3 text-center transition-all duration-200
                                        border-gray-200 dark:border-gray-600 text-gray-400 dark:text-gray-500
                                        peer-checked:border-green-500 peer-checked:text-green-600 peer-checked:bg-green-50
                                        dark:peer-checked:border-green-400 dark:peer-checked:text-green-400 dark:peer-checked:bg-green-500/10
                                        hover:border-gray-300 dark:hover:border-gray-500">
                                        <i class="fas fa-chalkboard-teacher text-xl mb-1 block"></i>
                                        <span class="text-xs font-semibold">Teacher</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="user_type_radio" value="other" class="peer sr-only" checked>
                                    <div class="border-2 rounded-xl p-3 text-center transition-all duration-200
                                        border-gray-200 dark:border-gray-600 text-gray-400 dark:text-gray-500
                                        peer-checked:border-purple-500 peer-checked:text-purple-600 peer-checked:bg-purple-50
                                        dark:peer-checked:border-purple-400 dark:peer-checked:text-purple-400 dark:peer-checked:bg-purple-500/10
                                        hover:border-gray-300 dark:hover:border-gray-500">
                                        <i class="fas fa-user-tie text-xl mb-1 block"></i>
                                        <span class="text-xs font-semibold">Employee</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Name + email --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                        </div>

                        {{-- Password (edit only) --}}
                        <div id="passwordField" class="hidden">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                New Password
                            </label>
                            <input type="password" id="password" name="password"
                                class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-transparent px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:bg-gray-900 transition-colors"
                                placeholder="Leave blank to keep current password">
                            <p id="passwordError" class="mt-1 text-xs text-red-500 hidden"></p>
                        </div>

                        {{-- Auto-password notice (create only) --}}
                        <div id="autoPasswordNotice" class="flex items-center gap-2 rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 px-4 py-3">
                            <i class="fas fa-key text-amber-500 text-sm"></i>
                            <p class="text-xs text-amber-700 dark:text-amber-400">
                                Password is auto-generated as <strong>first 3 letters of name + 123</strong> (e.g. <em>Abshar</em> → <strong>abs123</strong>)
                            </p>
                        </div>

                        {{-- Student section --}}
                        <div id="studentSection" class="hidden">
                            <div class="rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 p-4 space-y-4">
                                <p class="text-xs font-semibold text-blue-700 dark:text-blue-400 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fas fa-user-graduate"></i> Student Details
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="admission_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                            Admission No. <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="admission_no" name="admission_no"
                                            class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors"
                                            placeholder="e.g. ADM001">
                                        <p id="admission_noError" class="mt-1 text-xs text-red-500 hidden"></p>
                                    </div>
                                    <div>
                                        <label for="s_class_group_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                            Class Group
                                        </label>
                                        <select id="s_class_group_id"
                                            class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 text-sm text-gray-800 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-colors">
                                            <option value="">Select class group</option>
                                            @foreach ($classGroups as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                        <p id="s_class_group_idError" class="mt-1 text-xs text-red-500 hidden"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Teacher section --}}
                        <div id="teacherSection" class="hidden">
                            <div class="rounded-xl bg-green-50 dark:bg-green-500/10 border border-green-100 dark:border-green-500/20 p-4 space-y-4">
                                <p class="text-xs font-semibold text-green-700 dark:text-green-400 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fas fa-chalkboard-teacher"></i> Teacher Details
                                </p>
                                <div>
                                    <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        Subject
                                    </label>
                                    <input type="text" id="subject" name="subject"
                                        class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 text-sm text-gray-800 dark:text-white placeholder:text-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-colors"
                                        placeholder="e.g. Mathematics">
                                    <p id="subjectError" class="mt-1 text-xs text-red-500 hidden"></p>
                                </div>
                                <div>
                                    <label for="t_role_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        Teacher Role <span class="text-red-500">*</span>
                                    </label>
                                    @if($teacherRoles->isEmpty())
                                        <p class="text-xs text-amber-600 dark:text-amber-400 flex items-center gap-1.5">
                                            <i class="fas fa-triangle-exclamation"></i>
                                            No teacher roles found. Please create a role with "teacher" in its name first.
                                        </p>
                                    @else
                                        <select id="t_role_id"
                                            class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 text-sm text-gray-800 dark:text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-colors">
                                            <option value="">Select teacher role</option>
                                            @foreach ($teacherRoles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    <p id="t_role_idError" class="mt-1 text-xs text-red-500 hidden"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Employee section --}}
                        <div id="otherSection">
                            <div class="rounded-xl bg-purple-50 dark:bg-purple-500/10 border border-purple-100 dark:border-purple-500/20 p-4 space-y-4">
                                <p class="text-xs font-semibold text-purple-700 dark:text-purple-400 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fas fa-user-tie"></i> Employee Details
                                </p>
                                <div>
                                    <label for="o_role_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                        Role
                                    </label>
                                    <select id="o_role_id"
                                        class="w-full h-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 px-4 text-sm text-gray-800 dark:text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-colors">
                                        <option value="">Select role</option>
                                        @foreach ($otherRoles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <p id="o_role_idError" class="mt-1 text-xs text-red-500 hidden"></p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700 sticky bottom-0 bg-white dark:bg-gray-800 rounded-b-2xl z-10">
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

            function switchType(type) {
                $('#userType').val(type);
                $('input[name="user_type_radio"][value="' + type + '"]').prop('checked', true);
                $('#studentSection, #teacherSection, #otherSection').addClass('hidden');
                $('#' + type + 'Section').removeClass('hidden');
                clearErrors();
            }

            $('input[name="user_type_radio"]').on('change', function () {
                if (!$(this).prop('disabled')) {
                    switchType($(this).val());
                }
            });

            function openModal(title, isEdit) {
                $('#modalTitle').text(title);
                if (isEdit) {
                    $('#passwordField').removeClass('hidden');
                    $('#autoPasswordNotice').addClass('hidden');
                } else {
                    $('#passwordField').addClass('hidden');
                    $('#autoPasswordNotice').removeClass('hidden');
                    $('#password').val('');
                }
                $('#formModal, #modalBackdrop').removeClass('hidden');
                setTimeout(() => $('#modalContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'), 10);
            }

            function closeModal() {
                $('#modalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
                setTimeout(function () {
                    $('#formModal, #modalBackdrop').addClass('hidden');
                    resetForm();
                }, 200);
            }

            function resetForm() {
                $('#crudForm')[0].reset();
                $('#recordId').val('');
                $('input[name="user_type_radio"]').prop('disabled', false);
                switchType('other');
                clearErrors();
            }

            function clearErrors() {
                $('[id$="Error"]').addClass('hidden').text('');
            }

            function showErrors(errors) {
                clearErrors();
                const type = $('#userType').val();
                const fieldMap = {
                    student: { class_group_id: 's_class_group_idError' },
                    teacher: { role_id: 't_role_idError' },
                    other:   { role_id: 'o_role_idError' },
                };
                $.each(errors, function (field, messages) {
                    const override = fieldMap[type] && fieldMap[type][field];
                    const domId = override || (field + 'Error');
                    $('#' + domId).removeClass('hidden').text(messages[0]);
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
                        if (!res.success) return;

                        const d    = res.data;
                        const type = res.user_type;

                        $('#recordId').val(d.id);
                        $('#name').val(d.name);
                        $('#email').val(d.email);
                        $('#password').val('');

                        switchType(type);
                        $('input[name="user_type_radio"]').prop('disabled', true);

                        if (type === 'student' && d.student) {
                            $('#admission_no').val(d.student.admission_no);
                            $('#s_class_group_id').val(d.student.class_group_id);
                        } else if (type === 'teacher' && d.teacher) {
                            $('#subject').val(d.teacher.subject);
                            $('#t_role_id').val(d.roles?.[0]?.id);
                        } else {
                            $('#o_role_id').val(d.roles?.[0]?.id);
                        }

                        openModal('Edit User', true);
                    },
                    error: function () {
                        showToast('Failed to load user data.', 'error');
                    }
                });
            });

            $('#crudForm').on('submit', function (e) {
                e.preventDefault();
                clearErrors();

                const id     = $('#recordId').val();
                const isEdit = id !== '';
                const url    = isEdit ? BASE_URL + '/' + id : BASE_URL;
                const method = isEdit ? 'PUT' : 'POST';
                const type   = $('#userType').val();

                $('#btnSubmitSpinner').removeClass('hidden');
                $('#btnSubmitText').text(isEdit ? 'Updating...' : 'Saving...');

                const formData = {
                    _token:    CSRF_TOKEN,
                    user_type: type,
                    name:      $('#name').val(),
                    email:     $('#email').val(),
                };

                if (isEdit) {
                    const pwd = $('#password').val();
                    if (pwd) formData.password = pwd;
                }

                if (type === 'student') {
                    formData.admission_no   = $('#admission_no').val();
                    formData.class_group_id = $('#s_class_group_id').val();
                } else if (type === 'teacher') {
                    formData.subject = $('#subject').val();
                    formData.role_id = $('#t_role_id').val();
                } else {
                    formData.role_id = $('#o_role_id').val();
                }

                $.ajax({
                    url:  url,
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
                            showToast(xhr.responseJSON?.message || 'An error occurred. Please try again.', 'error');
                        }
                    },
                    complete: function () {
                        $('#btnSubmitSpinner').addClass('hidden');
                        $('#btnSubmitText').text('Save');
                    }
                });
            });

            $(document).on('click', '.btn-delete', function () {
                const id  = $(this).data('id');
                const row = $(this).closest('tr');
                if (!confirm('Are you sure you want to delete this user? Associated student/teacher record will also be removed.')) return;

                $.ajax({
                    url:  BASE_URL + '/' + id,
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
            });
        });
    </script>
@endpush
