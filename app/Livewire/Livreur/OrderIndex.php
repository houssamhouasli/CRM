<?php

namespace App\Livewire\Livreur;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class OrderIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $status = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

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
        $this->status = '';
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
    }
    public function render()
    {
        $query = Order::with(['client', 'creator'])
            ->whereHas('creator', function ($q) {
                $q->where('depot_id', auth()->user()->depot_id);
            });

        if ($this->search) {
            $query->where(function($q) {
                $q->where('id', 'like', "%{$this->search}%")
                  ->orWhereHas('client', function($qc) {
                      $qc->where('company_name', 'like', "%{$this->search}%");
                  });
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->sortField === 'client') {
            $query->join('clients', 'orders.client_id', '=', 'clients.id')
                ->orderBy('clients.company_name', $this->sortDirection)
                ->select('orders.*');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $orders = $query->paginate(10);

        return view('livewire.livreur.order-index', compact('orders'));
    }
}
