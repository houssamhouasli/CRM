<?php

namespace App\Livewire\Depositaire;

use App\Models\StockMovement;
use Livewire\Component;
use Livewire\WithPagination;

class StockMovementIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $type = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->type = '';
        $this->sortField = 'id';
        $this->sortDirection = 'asc';
    }

    public function render()
    {
        $depotId = auth()->user()->depot_id;

        $query = StockMovement::with(['product', 'user'])
            ->where('depot_id', $depotId);

        if ($this->type !== '') {
            $query->where('type', $this->type);
        }

        if ($this->search !== '') {
            $query->where(function($q) {
                $q->whereHas('product', function($q2) {
                    $q2->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%');
                })->orWhere('reason', 'like', '%' . $this->search . '%')
                  ->orWhere('id', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->sortField === 'product_name') {
            $query->join('products', 'stock_movements.product_id', '=', 'products.id')
                  ->select('stock_movements.*')
                  ->orderBy('products.name', $this->sortDirection);
        } else {
            $query->orderBy('stock_movements.' . $this->sortField, $this->sortDirection);
        }

        $movements = $query->paginate(15);

        return view('livewire.depositaire.stock-movement-index', compact('movements'));
    }
}
