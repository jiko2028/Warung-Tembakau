<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')->latest()->paginate(10);
        return view('admin.produk.index', compact('produks'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama')->get();
        return view('admin.produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'berat' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean'
        ]);

        $path = $request->file('foto')->store('produk', 'public');

        Produk::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama) . '-' . time(),
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'berat' => $request->berat,
            'stok' => $request->stok,
            'foto' => $path,
            'is_active' => $request->input('is_active', true)
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        $kategoris = Kategori::orderBy('nama')->get();
        return view('admin.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'berat' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = [
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama) . '-' . $produk->id, // Simple unique slug handler
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'berat' => $request->berat,
            'stok' => $request->stok,
            'is_active' => $request->input('is_active', false)
        ];

        if ($request->hasFile('foto')) {
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        // Fitur Soft Delete Otomatis jika menggunakan trait SoftDeletes di model
        $produk->delete();
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus (Dipindahkan ke keranjang sampah).');
    }

    public function toggleStatus(Produk $produk)
    {
        $produk->update([
            'is_active' => !$produk->is_active
        ]);

        $status = $produk->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Produk berhasil $status.");
    }
}
