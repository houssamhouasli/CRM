<?php

namespace App\Livewire\Commercial;

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

    public function resetFilters()
    {
        $this->search = '';
        $this->category_id = '';
    }

    public function render()
    {
        $query = Product::with('category');

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category_id !== '') {
            $query->where('category_id', $this->category_id);
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('livewire.commercial.product-index', compact('products', 'categories'));
    }
}
