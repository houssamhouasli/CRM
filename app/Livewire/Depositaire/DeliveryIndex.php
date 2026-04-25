<?php

namespace App\Livewire\Depositaire;

use App\Models\Delivery; 
use Livewire\Component;
use Livewire\WithPagination;

class DeliveryIndex extends Component
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
        $depotId = auth()->user()->depot_id;

        $query = Delivery::with(['order.client', 'livreur'])
            ->where('deliveries.depot_id', $depotId);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('deliveries.id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('order.client', function ($q2) {
                        $q2->where('company_name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('livreur', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->sortField === 'client_name') {
            $query->join('orders', 'deliveries.order_id', '=', 'orders.id')
                  ->leftJoin('clients', 'orders.client_id', '=', 'clients.id')
                  ->select('deliveries.*')
                  ->orderBy('clients.company_name', $this->sortDirection);
        } elseif ($this->sortField === 'livreur_name') {
            $query->leftJoin('users', 'deliveries.livreur_id', '=', 'users.id')
                  ->select('deliveries.*')
                  ->orderBy('users.name', $this->sortDirection);
        } else {
            $query->orderBy('deliveries.' . $this->sortField, $this->sortDirection);
        }

        $deliveries = $query->paginate(10);

        return view('livewire.depositaire.delivery-index', [
            'deliveries' => $deliveries
        ]);
    }
}
