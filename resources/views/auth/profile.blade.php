@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-8">Profil Pengguna</h1>

    {{-- Pesan Flash --}}
    @include('partials._flash_messages')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg mb-8">
                <h3 class="text-2xl font-semibold text-gray-800 mb-6">Informasi Profil</h3>
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="inline-flex justify-center py-2.5 px-5 border border-transparent shadow-md text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out transform hover:scale-105">
                            Perbarui Profil
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg">
                <h3 class="text-2xl font-semibold text-gray-800 mb-6">Perbarui Password</h3>
                <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                        <input id="current_password" type="password" name="current_password" required
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input id="password" type="password" name="password" required
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                               class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="inline-flex justify-center py-2.5 px-5 border border-transparent shadow-md text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out transform hover:scale-105">
                            Perbarui Password
                        </button>
                    </div>
                </form>
            </div>
        </div>


        </div>
    </div>
</div>
@endsection