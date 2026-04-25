<?php

namespace App\Livewire\Admin;

use App\Models\StockMovement;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class StockMovementIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $product_id = '';
    public $type = '';
    public $search_id = '';
    public $sort = 'id';
    public $sortDirection = 'asc';

    public function sortBy($column)
    {
        if ($this->sort === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $column;
            $this->sortDirection = 'desc';
        }
    }

    public function resetFilters()
    {
        $this->product_id = '';
        $this->type = '';
        $this->search_id = '';
        $this->sort = 'moved_at';
        $this->sortDirection = 'desc';
    }

    public function render()
    {
        $query = StockMovement::with(['product', 'user', 'order']);

        if ($this->search_id !== '') {
            $query->where('stock_movements.id', 'like', '%' . $this->search_id . '%');
        }

        if ($this->product_id !== '') {
            $query->where('product_id', $this->product_id);
        }

        if ($this->type !== '') {
            $query->where('type', $this->type);
        }

        if ($this->sort) {
            if ($this->sort === 'product_name') {
                 $query->join('products', 'stock_movements.product_id', '=', 'products.id')
                       ->select('stock_movements.*')
                       ->orderBy('products.name', $this->sortDirection);
            } else if ($this->sort === 'user_name') {
                 $query->join('users', 'stock_movements.user_id', '=', 'users.id')
                       ->select('stock_movements.*')
                       ->orderBy('users.name', $this->sortDirection);
            } else {
                 $query->orderBy('stock_movements.' . $this->sort, $this->sortDirection);
            }
        }

        $movements = $query->paginate(15);
        $products = Product::all();

        return view('livewire.admin.stock-movement-index', compact('movements', 'products'));
    }
}
