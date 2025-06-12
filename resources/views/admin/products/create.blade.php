@extends('layouts.admin_app')
@section('title', 'Tambah Produk Baru')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800">Tambah Produk Baru</h1>
        <a href="{{ route('admin.products.index') }}" class="mt-4 sm:mt-0 inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-150">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
            Kembali ke Daftar Produk
        </a>
    </div>

    {{-- Pesan Flash (jika ada dari validasi atau aksi sebelumnya) --}}
    @include('partials._flash_messages') {{-- Asumsi Anda membuat partial untuk pesan flash --}}

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- 
                Menggunakan partial form.
                Untuk 'create', $product tidak ada, jadi kita bisa mengoper null atau tidak mengopernya jika _form menghandle isset($product).
                $allRegisteredSizes adalah variabel yang dikirim dari ProductController@create.
            --}}
            @include('admin.products._form', ['product' => null, 'allRegisteredSizes' => $allRegisteredSizes])
        </form>
    </div>
</div>
@endsection
