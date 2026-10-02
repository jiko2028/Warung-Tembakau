<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::where('slug', '!=', 'umum')->latest()->paginate(10);
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        Kategori::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        $kategori->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $jumlahProduk = $kategori->produk()->count();

        if ($jumlahProduk > 0) {
            // Cari atau buat kategori "Umum" sebagai fallback
            $fallback = Kategori::firstOrCreate(
                ['slug' => 'umum'],
                ['nama' => 'Umum', 'deskripsi' => 'Kategori umum untuk produk tanpa kategori spesifik.']
            );

            // Jangan hapus jika kategori yang dihapus IS the fallback category
            if ($kategori->id === $fallback->id) {
                return redirect()->route('admin.kategori.index')
                    ->with('error', 'Kategori "Umum" tidak bisa dihapus karena digunakan sebagai fallback.');
            }

            $kategori->produk()->update(['kategori_id' => $fallback->id]);
        }

        $kategori->delete();

        $pesan = 'Kategori "' . $kategori->nama . '" berhasil dihapus.';
        if ($jumlahProduk > 0) {
            $pesan .= " $jumlahProduk produk dipindahkan ke kategori Umum.";
        }

        return redirect()->route('admin.kategori.index')->with('success', $pesan);
    }
}
