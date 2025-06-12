@extends('layouts.admin_app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8"> 
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800">Manajemen Pengguna</h1>
        {{-- Tombol Tambah Pengguna Baru bisa ditambahkan di sini jika diperlukan --}}
        {{-- <a href="#" class="mt-4 sm:mt-0 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md transition-colors duration-150">
            Tambah Pengguna Baru
        </a> --}}
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-md" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-md" role="alert">
            <p class="font-bold">Error!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif
    @if($users->isEmpty())
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <p class="text-gray-600 text-xl">Belum ada pengguna terdaftar (selain admin saat ini).</p>
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terdaftar Sejak</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-100 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-pink-100 text-pink-800' : 'bg-sky-100 text-sky-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if(Auth::user()->id !== $user->id && $user->role !== 'admin') {{-- Mencegah admin menghapus diri sendiri atau admin lain --}}
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('PERINGATAN: Anda akan menghapus pengguna {{ $user->name }}. Tindakan ini tidak dapat diurungkan. Lanjutkan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold transition-colors duration-150">Hapus</button>
                                    </form>
                                @endif
                                {{-- Contoh Tombol Edit (jika ada fungsionalitasnya) --}}
                                {{-- <a href="#" class="text-indigo-600 hover:text-indigo-800 font-semibold ml-3 transition-colors duration-150">Edit</a> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection