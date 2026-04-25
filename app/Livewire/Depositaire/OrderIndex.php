<?php

namespace App\Livewire\Depositaire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderIndex extends Component
{ 
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $status = '';
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
        $this->status = '';
        $this->sort = 'id';
        $this->sortDirection = 'asc';
    }

    public function render()
    {
        $query = Order::with('client.region')->where('orders.type', 'sale')
            ->where(function($q) {
                $q->whereHas('deliveries', function($d) {
                    $d->where('depot_id', auth()->user()->depot_id);
                })->orWhereDoesntHave('deliveries');
            });

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('client', function ($q2) {
                        $q2->where('company_name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->status !== '') {
            $query->where('orders.status', $this->status);
        }

        if ($this->sort) {
            if ($this->sort === 'client_name') {
                $query->join('clients', 'orders.client_id', '=', 'clients.id')
                    ->select('orders.*')
                    ->orderBy('clients.company_name', $this->sortDirection);
            } else if ($this->sort === 'region_name') {
                $query->join('clients', 'orders.client_id', '=', 'clients.id')
                    ->join('regions', 'clients.region_id', '=', 'regions.id')
                    ->select('orders.*')
                    ->orderBy('regions.name', $this->sortDirection);
            } else {
                $query->orderBy('orders.' . $this->sort, $this->sortDirection);
            }
        }

        $orders = $query->paginate(15);

        return view('livewire.depositaire.order-index', compact('orders'));
    }
}
