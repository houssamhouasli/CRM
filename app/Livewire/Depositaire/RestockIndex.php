<?php

namespace App\Livewire\Depositaire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class RestockIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

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
        $this->sortField = 'id';
        $this->sortDirection = 'asc';
        $this->search = '';
    }

    public function render()
    {
        $orders = Order::where('created_by', auth()->id())
            ->where('type', 'restock')
            ->where(function($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                      ->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.depositaire.restock-index', [
            'orders' => $orders
        ]);
    }
}
