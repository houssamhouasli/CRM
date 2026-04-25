<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $category_id = '';
    public $price_min = '';
    public $price_max = '';

    public $sort = 'id';
    public $sortDirection = 'asc';

    public function sortBy($column)
    {
        if ($this->sort === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $column;
            $this->sortDirection = 'asc'; 
        }
    }


    public function resetFilters()
    {
        $this->search = '';
        $this->category_id = '';
        $this->price_min = '';
        $this->price_max = '';
        $this->sort = 'id';
        $this->sortDirection = 'asc';
    }

    public function render()
    {
        $query = Product::with('category');

        if ($this->search !== '') {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
        }

        if ($this->category_id !== '') {
            $query->where('category_id', $this->category_id);
        }

        if ($this->price_min !== '') {
            $query->where('price_ht', '>=', $this->price_min);
        }

        if ($this->price_max !== '') {
            $query->where('price_ht', '<=', $this->price_max);
        }

        if ($this->sort) {
            if ($this->sort === 'category_name') {
                $query->join('categories', 'products.category_id', '=', 'categories.id')
                      ->select('products.*')
                      ->orderBy('categories.name', $this->sortDirection);
            } elseif ($this->sort === 'stock_quantity') {
                $query->orderBy('products.id', $this->sortDirection);
            } else {
                $query->orderBy('products.' . $this->sort, $this->sortDirection);
            }
        }

        $products = $query->paginate(10);

        $categories = Category::all();

        return view('livewire.admin.product-index', compact('products', 'categories'));
    }
}
