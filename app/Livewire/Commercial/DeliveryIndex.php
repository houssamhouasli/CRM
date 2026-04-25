<?php

namespace App\Livewire\Commercial;

use App\Models\Delivery;
use Livewire\Component;
use Livewire\WithPagination;

class DeliveryIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $status = '';
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
        $this->search = '';
        $this->sort = 'id';
        $this->sortDirection = 'asc';
    }

    public function render()
    {
        $regionId = auth()->user()->region_id;

        $query = Delivery::with(['order.client', 'livreur', 'depot'])
            ->whereHas('order.client', function($q) use ($regionId) {
                $q->where('region_id', $regionId);
            });

        if ($this->search !== '') {
            $query->where(function($q) {
                $q->where('deliveries.id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('order', function($q2) {
                      $q2->where('orders.id', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('order.client', function($q3) {
                      $q3->where('company_name', 'like', '%' . strtolower($this->search) . '%');
                  });
            });
        }

        if ($this->status !== '') {
            $query->where('deliveries.status', $this->status);
        }

        if ($this->sort) {
            if ($this->sort === 'client') {
                $query->join('orders', 'deliveries.order_id', '=', 'orders.id')
                      ->join('clients', 'orders.client_id', '=', 'clients.id')
                      ->select('deliveries.*')
                      ->orderBy('clients.company_name', $this->sortDirection);
            } elseif ($this->sort === 'order') {
                $query->orderBy('deliveries.order_id', $this->sortDirection);
            } elseif ($this->sort === 'livreur') {
                $query->join('users', 'deliveries.livreur_id', '=', 'users.id')
                      ->select('deliveries.*')
                      ->orderBy('users.name', $this->sortDirection);
            } else {
                $query->orderBy('deliveries.' . $this->sort, $this->sortDirection);
            }
        }

        $deliveries = $query->paginate(10);

        return view('livewire.commercial.delivery-index', compact('deliveries'));
    }
}
