<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // GET /api/products?categoria=X  — catálogo público (solo activos)
    public function index(Request $request)
    {
        $query = Product::where('activo', true);

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        return response()->json($query->orderBy('nombre')->get());
    }

    // GET /api/admin/products  — todos los productos para el panel admin
    public function adminIndex(Request $request)
    {
        $query = Product::query();

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        return response()->json($query->orderBy('nombre')->get());
    }

    // GET /api/products/{id}
    public function show($id)
    {
        return response()->json(Product::where('activo', true)->findOrFail($id));
    }

    // POST /api/admin/products
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'precio'      => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'categoria'   => 'required|in:accesorios,electronica,fundas,parlantes,termos',
            'moneda'      => 'required|in:ARS,USD',
            'activo'      => 'boolean',
        ]);

        return response()->json(Product::create($data), 201);
    }

    // PUT /api/admin/products/{id}
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'nombre'      => 'sometimes|string|max:255',
            'precio'      => 'sometimes|numeric|min:0',
            'descripcion' => 'nullable|string',
            'categoria'   => 'sometimes|in:accesorios,electronica,fundas,parlantes,termos',
            'moneda'      => 'sometimes|in:ARS,USD',
            'activo'      => 'boolean',
        ]);

        $product->update($data);
        return response()->json($product);
    }

    // DELETE /api/admin/products/{id}
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->imagen) {
            Storage::disk('public')->delete($product->imagen);
        }

        $product->delete();
        return response()->json(null, 204);
    }

    // POST /api/admin/products/{id}/imagen
    public function uploadImage(Request $request, $id)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $product = Product::findOrFail($id);

        if ($product->imagen) {
            Storage::disk('public')->delete($product->imagen);
        }

        $path = $request->file('imagen')->store('productos', 'public');
        $product->update(['imagen' => $path]);

        return response()->json([
            'imagen' => $path,
            'url'    => Storage::url($path),
        ]);
    }
}
