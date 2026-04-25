<?php

namespace App\Livewire\Depositaire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ReturnModel;

class ReturnIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->sortField = 'id';
        $this->sortDirection = 'asc';
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $user = auth()->user();

        $query = ReturnModel::with(['delivery.order.client', 'livreur'])
            ->where('returns.depot_id', $user->depot_id);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('delivery.order.client', function ($q) {
                        $q->where('company_name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Handle sorting by related fields
        if ($this->sortField === 'client') {
            $query->join('deliveries', 'returns.delivery_id', '=', 'deliveries.id')
                ->join('orders', 'deliveries.order_id', '=', 'orders.id')
                ->join('clients', 'orders.client_id', '=', 'clients.id')
                ->orderBy('clients.company_name', $this->sortDirection)
                ->select('returns.*');
        } elseif ($this->sortField === 'order_id') {
            $query->join('deliveries', 'returns.delivery_id', '=', 'deliveries.id')
                ->orderBy('deliveries.order_id', $this->sortDirection)
                ->select('returns.*');
        } elseif ($this->sortField === 'livreur') {
            $query->join('users', 'returns.livreur_id', '=', 'users.id')
                ->orderBy('users.name', $this->sortDirection)
                ->select('returns.*');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $returns = $query->paginate(10);

        return view('livewire.depositaire.return-index', [
            'returns' => $returns,
        ]);
    }
}
