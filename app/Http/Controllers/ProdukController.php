<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('kategori')->where('is_active', true);

        // Filter by Category
        if ($request->has('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        // Search
        if ($request->has('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }

        // Sort
        switch ($request->get('sort')) {
            case 'price_asc':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('harga', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = Kategori::all();

        return view('produk.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $produk = Produk::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Ambil produk terkait (prioritas kategori sama, lalu random) - Total 8
        $relatedProducts = Produk::where('kategori_id', $produk->kategori_id)
            ->where('id', '!=', $produk->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(8)
            ->get();
            
        // Jika kurang dari 8, ambil produk random lainnya untuk melengkapi
        if ($relatedProducts->count() < 8) {
            $existingIds = $relatedProducts->pluck('id')->push($produk->id);
            $moreProducts = Produk::whereNotIn('id', $existingIds)
                ->where('is_active', true)
                ->inRandomOrder()
                ->take(8 - $relatedProducts->count())
                ->get();
                
            $relatedProducts = $relatedProducts->merge($moreProducts);
        }

        return view('produk.show', compact('produk', 'relatedProducts'));
    }
}
