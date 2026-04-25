<?php

namespace App\Livewire\Livreur;

use App\Models\TruckStock;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class TruckInventory extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryId = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    protected $paginationTheme = 'bootstrap';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->categoryId = '';
        $this->sortField = 'id';
        $this->sortDirection = 'asc';
    }


    public function render()
    {
        $livreur = auth()->user();
        if (!$livreur->truck) {
             return view('livewire.livreur.truck-inventory', ['stocks' => collect()]);
        }

        $query = TruckStock::with('product.category')
            ->where('truck_id', $livreur->truck->id);

        if ($this->search) {
            $query->whereHas('product', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryId) {
            $query->whereHas('product', function($q) {
                $q->where('category_id', $this->categoryId);
            });
        }

        if ($this->sortField === 'product_name') {
            $query->join('products', 'truck_stocks.product_id', '=', 'products.id')
                  ->orderBy('products.name', $this->sortDirection)
                  ->select('truck_stocks.*');
        } elseif ($this->sortField === 'quantity') {
            $query->orderBy('quantity', $this->sortDirection);
        } elseif ($this->sortField === 'sku') {
            $query->join('products', 'truck_stocks.product_id', '=', 'products.id')
                  ->orderBy('products.sku', $this->sortDirection)
                  ->select('truck_stocks.*');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $stocks = $query->paginate(15);
        $categories = Category::orderBy('name')->get();

        return view('livewire.livreur.truck-inventory', compact('stocks', 'categories'));
    }
}
