<?php

namespace App\Livewire\Admin;

use App\Models\Delivery;
use App\Models\Depot;
use Livewire\Component;
use Livewire\WithPagination;

class DeliveryIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $depot = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public function resetFilters()
    {
        $this->search = '';
        $this->status = '';
        $this->depot = '';
        $this->sortField = 'id';
        $this->sortDirection = 'desc';
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
        $depots = Depot::all();
        $deliveries = Delivery::with(['order.client', 'livreur', 'depot'])
            ->when($this->search, function($query) {
                $query->whereHas('order.client', function($q) {
                    $q->where('company_name', 'like', '%' . $this->search . '%');
                })->orWhere('id', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function($query) {
                $query->where('status', $this->status);
            })
            ->when($this->depot, function($query) {
                $query->where('depot_id', $this->depot);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.delivery-index', compact('deliveries','depots'));
    }
}
