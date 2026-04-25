<?php

namespace App\Livewire\Livreur;

use App\Models\Delivery;
use Livewire\Component;
use Livewire\WithPagination;

class DeliveryList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

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
        $this->status = '';
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
    }

    public function render()
    {
        $query = Delivery::with(['order.client', 'depot'])
            ->where('livreur_id', auth()->id());

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('order.client', function ($q2) {
                      $q2->where('company_name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->sortField === 'client_name') {
            $query->join('orders', 'deliveries.order_id', '=', 'orders.id')
                  ->join('clients', 'orders.client_id', '=', 'clients.id')
                  ->orderBy('clients.company_name', $this->sortDirection)
                  ->select('deliveries.*');
        } elseif ($this->sortField === 'order_id') {
            $query->orderBy('order_id', $this->sortDirection);
        } elseif ($this->sortField === 'delivery_date') {
            $query->orderBy('delivery_date', $this->sortDirection);
        } elseif ($this->sortField === 'status') {
            $query->orderBy('status', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $deliveries = $query->paginate(10);

        return view('livewire.livreur.delivery-list', compact('deliveries'));
    }
}
