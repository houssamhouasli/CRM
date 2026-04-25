<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index');
    }

    public function create() 
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_ht' => 'required|numeric|min:0',
            'tva_rate' => 'required|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:20',
            'promo_type' => 'nullable|in:percentage,fixed',
            'promo_value' => 'nullable|numeric|min:0',
            'promo_min_qty' => 'nullable|integer|min:1',
            'promo_start_date' => 'nullable|date',
            'promo_end_date' => 'nullable|date|after_or_equal:promo_start_date',
        ]);

        $product = Product::create($request->only(
            'name', 'sku', 'category_id', 'price_ht', 'tva_rate', 'weight', 'unit',
            'promo_type', 'promo_value', 'promo_min_qty', 'promo_start_date', 'promo_end_date'
        ));


        // Initial stock no longer created here since stock requires truck or depot id

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_ht' => 'required|numeric|min:0',
            'tva_rate' => 'required|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:20',
            'promo_type' => 'nullable|in:percentage,fixed',
            'promo_value' => 'nullable|numeric|min:0',
            'promo_min_qty' => 'nullable|integer|min:1',
            'promo_start_date' => 'nullable|date',
            'promo_end_date' => 'nullable|date|after_or_equal:promo_start_date',
        ]);

        $product->update($request->only(
            'name', 'sku', 'category_id', 'price_ht', 'tva_rate', 'weight', 'unit',
            'promo_type', 'promo_value', 'promo_min_qty', 'promo_start_date', 'promo_end_date'
        ));

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        if ($product->orderItems()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des commandes référencent ce produit.');
        }
        
        $product->stockMovements()->delete();
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé avec son historique.');
    }



    // ── Categories ──
    public function categories()
    {
        $categories = Category::with('products')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function showCategory(Category $category)
    {
        $products = $category->products()->paginate(10);
        return view('admin.categories.show', compact('category', 'products'));
    }

    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($request->only('name', 'description'));
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->only('name', 'description'));
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : des produits sont dans cette catégorie.');
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie supprimée.');
    }

    // ── Stock Management ──
    public function stockMovements()
    {
        return view('admin.stock.index');
    }
}
