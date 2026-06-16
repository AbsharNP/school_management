@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">

        {{-- Profile Info Card --}}
        <div class="col-span-12 md:col-span-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-brand-100 dark:bg-brand-500/20 flex items-center justify-center mb-4">
                    <i class="fas fa-user text-3xl text-brand-500"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $user->email }}</p>
                <div class="mt-3 flex flex-wrap justify-center gap-1">
                    @foreach ($user->roles as $role)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand-100 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Change Password Card --}}
        <div class="col-span-12 md:col-span-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">Change Password</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Update your account password. Minimum 8 characters.</p>

                @if (session('success'))
                    <div class="mb-4 flex items-center gap-3 rounded-lg bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 px-4 py-3">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span class="text-sm font-medium text-green-700 dark:text-green-400">{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 flex items-start gap-3 rounded-lg bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 px-4 py-3">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <ul class="text-sm text-red-700 dark:text-red-400 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Current Password
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="current_password"
                                    name="current_password"
                                    autocomplete="current-password"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2.5 pr-10 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition @error('current_password') border-red-400 focus:ring-red-400 @enderror"
                                    placeholder="Enter current password"
                                >
                                <button type="button" class="toggle-password absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" data-target="current_password">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                New Password
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="new_password"
                                    name="new_password"
                                    autocomplete="new-password"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2.5 pr-10 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition @error('new_password') border-red-400 focus:ring-red-400 @enderror"
                                    placeholder="Enter new password (min 8 chars)"
                                >
                                <button type="button" class="toggle-password absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" data-target="new_password">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                            @error('new_password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Confirm New Password
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="new_password_confirmation"
                                    name="new_password_confirmation"
                                    autocomplete="new-password"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2.5 pr-10 text-sm text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition"
                                    placeholder="Repeat new password"
                                >
                                <button type="button" class="toggle-password absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" data-target="new_password_confirmation">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 text-white rounded-lg hover:bg-brand-600 transition-all duration-200 shadow-sm hover:shadow-md font-medium text-sm">
                            <i class="fas fa-lock text-xs"></i>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(this.dataset.target);
            var icon  = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
</script>
@endpush
