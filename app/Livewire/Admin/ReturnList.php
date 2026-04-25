<?php

namespace App\Livewire\Admin;

use App\Models\ReturnModel;
use Livewire\Component;
use Livewire\WithPagination;

class ReturnList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    protected $paginationTheme = 'bootstrap';

    public function resetFilters()
    {
        $this->reset(['search', 'status', 'sortField', 'sortDirection']);
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
        $query = ReturnModel::with(['delivery.order.client', 'livreur', 'depot']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('delivery.order.client', function ($q2) {
                      $q2->where('company_name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('livreur', function ($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        $returns = $query->paginate(10);

        return view('livewire.admin.return-list', compact('returns'));
    }
}
