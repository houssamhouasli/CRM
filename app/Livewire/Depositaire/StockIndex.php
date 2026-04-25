<?php

namespace App\Livewire\Depositaire;

use App\Models\DepotStock;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class StockIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
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
        $this->search = '';
        $this->sort = 'id';
        $this->sortDirection = 'asc';
    }

    public function render()
    {
        $depotId = auth()->user()->depot_id;
        
        $query = DepotStock::with('product')
            ->where('depot_id', $depotId);

        if ($this->search !== '') {
            $query->whereHas('product', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->sort) {
            if ($this->sort === 'product_name') {
                 $query->join('products', 'depot_stocks.product_id', '=', 'products.id')
                       ->select('depot_stocks.*')
                       ->orderBy('products.name', $this->sortDirection);
            } else {
                 $query->orderBy('depot_stocks.' . $this->sort, $this->sortDirection);
            }
        }

        $stocks = $query->paginate(15);

        return view('livewire.depositaire.stock-index', compact('stocks'));
    }
}
