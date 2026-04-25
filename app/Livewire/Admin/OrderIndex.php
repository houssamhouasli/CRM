<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $status = '';
    public $type = '';
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
        $this->status = '';
        $this->type = '';
        $this->search = '';
        $this->sort = 'id';
        $this->sortDirection = 'desc';
    }

    public function render()
    {
        $query = Order::with(['client.region', 'creator.depot']);

        if ($this->search !== '') {
            $query->where(function($q) {
                $q->where('orders.id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('client', function($q2) {
                      $q2->where('company_name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('creator.depot', function($q3) {
                      $q3->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->status !== '') {
            $query->where('orders.status', $this->status);
        }

        if ($this->type !== '') {
            $query->where('orders.type', $this->type);
        }

        if ($this->sort) {
            if ($this->sort === 'client_name') {
                 $query->leftJoin('clients', 'orders.client_id', '=', 'clients.id')
                       ->select('orders.*')
                       ->orderBy('clients.company_name', $this->sortDirection);
            } else if ($this->sort === 'region_name') {
                 $query->leftJoin('clients', 'orders.client_id', '=', 'clients.id')
                       ->leftJoin('regions', 'clients.region_id', '=', 'regions.id')
                       ->select('orders.*')
                       ->orderBy('regions.name', $this->sortDirection);
            } else {
                 $query->orderBy('orders.' . $this->sort, $this->sortDirection);
            }
        }

        $orders = $query->paginate(15);

        return view('livewire.admin.order-index', compact('orders'));
    }
}
