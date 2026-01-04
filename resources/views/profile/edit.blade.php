<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>
    
    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6 sm:pb-4">
        {{-- Tombol Kembali --}}
        <div class="mb-6">
            <a href="{{ route('dashboard') }}"
            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 
                    text-sm font-medium transition-colors duration-200 
                    border-b-2 border-transparent hover:border-blue-300 pb-1">
                <x-heroicon-o-arrow-left class="w-4 h-4" />
                Kembali ke Dashboard
            </a>
        </div>

        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
