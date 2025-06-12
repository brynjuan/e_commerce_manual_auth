<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Ambil semua user kecuali admin yang sedang login (opsional)
        $users = User::where('id', '!=', auth()->id())->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Tambahan keamanan: pastikan admin tidak menghapus diri sendiri atau admin lain dari controller juga
        if (auth()->user()->id === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Akun admin tidak dapat dihapus melalui antarmuka ini.');
        }

        $user->delete(); // Ini akan melakukan hard delete

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}