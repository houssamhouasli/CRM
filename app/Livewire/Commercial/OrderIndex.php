<?php

namespace App\Livewire\Commercial;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderIndex extends Component 
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $status = '';
    public $search = '';
    public $sort = '';
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
        $this->status = '';
        $this->search = '';
        $this->sort = 'id';
        $this->sortDirection = 'asc';
    }

    public function render()
    {
        $regionId = auth()->user()->region_id;

        $query = Order::with('client')
            ->whereHas('client', function($q) use ($regionId) {
                $q->where('region_id', $regionId);
            });

        if ($this->search !== '') {
            $query->where(function($q) {
                $q->where('orders.id', 'like', '%' . strtolower($this->search) . '%')
                  ->orWhereHas('client', function($q2) {
                      $q2->where('company_name', 'like', '%' . strtolower($this->search) . '%');
                  });
            });
        }

        if ($this->status !== '') {
            $query->where('orders.status', $this->status);
        }

        if ($this->sort) {
            if ($this->sort === 'client') {
            $query->join('clients', 'orders.client_id', '=', 'clients.id')
                  ->select('orders.*')
                  ->orderBy('clients.company_name', $this->sortDirection);
        } else {
            $query->orderBy('orders.' . $this->sort, $this->sortDirection);
            }
        }

        $orders = $query->paginate(10);

        return view('livewire.commercial.order-index', compact('orders'));
    }
}
