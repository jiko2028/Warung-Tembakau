<?php

namespace App\Http\Controllers;

use App\Models\AlamatPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlamatPenggunaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'nama_penerima' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'provinsi' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:10',
            'alamat_lengkap' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        
        // Jika ini alamat pertama, set sebagai default
        $existingCount = AlamatPengguna::where('user_id', Auth::id())->count();
        $validated['is_default'] = $existingCount === 0;

        AlamatPengguna::create($validated);

        return back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $alamat = AlamatPengguna::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'nama_penerima' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'provinsi' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:10',
            'alamat_lengkap' => 'required|string',
        ]);

        $alamat->update($validated);

        return back()->with('success', 'Alamat berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $alamat = AlamatPengguna::where('user_id', Auth::id())->findOrFail($id);
        $alamat->delete();

        return back()->with('success', 'Alamat berhasil dihapus!');
    }

    public function setDefault($id)
    {
        // Hapus default dari semua alamat user ini
        AlamatPengguna::where('user_id', Auth::id())->update(['is_default' => false]);

        // Set alamat terpilih sebagai default
        $alamat = AlamatPengguna::where('user_id', Auth::id())->findOrFail($id);
        $alamat->update(['is_default' => true]);

        return back()->with('success', 'Alamat default berhasil diubah!');
    }
}
