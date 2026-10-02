<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function index()
    {
        $cartItems = Keranjang::with('produk')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('keranjang.index', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        
        // Check stock
        if ($produk->stok < $request->jumlah) {
            return response()->json(['message' => 'Stok tidak mencukupi'], 400);
        }

        $cartItem = Keranjang::where('user_id', Auth::id())
            ->where('produk_id', $request->produk_id)
            ->first();

        if ($cartItem) {
            $cartItem->jumlah += $request->jumlah;
            $cartItem->save();
        } else {
            Keranjang::create([
                'user_id' => Auth::id(),
                'produk_id' => $request->produk_id,
                'jumlah' => $request->jumlah,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Produk berhasil ditambahkan ke keranjang']);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function updateQty(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:keranjang,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $cartItem = Keranjang::where('user_id', Auth::id())->findOrFail($request->id);
        
        // Check stock again
        if ($cartItem->produk->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        }
        
        $cartItem->jumlah = $request->jumlah;
        $cartItem->save();

        return redirect()->back()->with('success', 'Keranjang diperbarui');
    }

    public function destroy(Keranjang $keranjang)
    {
        if ($keranjang->user_id !== Auth::id()) {
            abort(403);
        }

        $keranjang->delete();

        return redirect()->route('keranjang.index')->with('success', 'Produk dihapus dari keranjang');
    }
}
