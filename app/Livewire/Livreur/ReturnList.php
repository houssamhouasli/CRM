<?php

namespace App\Livewire\Livreur;

use App\Models\ReturnModel;
use Livewire\Component;
use Livewire\WithPagination;

class ReturnList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $sortField = 'id';
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
        $this->sortField = 'id';
        $this->sortDirection = 'desc';
    }

    public function render()
    {
        $query = ReturnModel::with(['delivery.order.client', 'returnItems'])
            ->where('livreur_id', auth()->id());

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('delivery.order.client', function ($q2) {
                      $q2->where('company_name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->sortField === 'delivery_id') {
            $query->orderBy('delivery_id', $this->sortDirection);
        } elseif ($this->sortField === 'status') {
            $query->orderBy('status', $this->sortDirection);
        } elseif ($this->sortField === 'id') {
            $query->orderBy('id', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $returns = $query->paginate(10);

        return view('livewire.livreur.return-list', compact('returns'));
    }
}
