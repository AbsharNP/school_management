@extends('layouts.app')

@section('content')
  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 md:col-span-4">
            <a href="{{ route('profile.show') }}" class="block group">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6 flex flex-col items-center text-center transition hover:shadow-lg hover:ring-2 hover:ring-brand-500">
                <div class="w-20 h-20 rounded-full bg-brand-100 dark:bg-brand-500/20 flex items-center justify-center mb-4">
                    <i class="fas fa-user text-3xl text-brand-500"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ auth()->user()->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ auth()->user()->email }}</p>
                <div class="mt-3 flex flex-wrap justify-center gap-1">
                    @foreach (auth()->user()->roles as $role)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand-100 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </div>
            </div>
            </a>
        </div>
  </div>
@endsection
