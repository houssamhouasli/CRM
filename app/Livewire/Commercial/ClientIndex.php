<?php

namespace App\Livewire\Commercial;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ClientIndex extends Component
{
    use WithPagination;
 
    protected $paginationTheme = 'bootstrap';

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
        $this->search = '';
        $this->sort = 'id';
        $this->sortDirection = 'desc';
    }

    public function render()
    {
        $regionId = auth()->user()->region_id;

        $query = Client::with('region')
            ->withSum(['deliveries' => function ($q) {
                $q->where('deliveries.status', 'livrer');
            }], 'total_ttc')
            ->where('region_id', $regionId);

        if ($this->search !== '') {
            $query->where(function($q) {
                $q->where('company_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('id', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->sort) {
            $sortColumn = $this->sort === 'total_ttc' ? 'deliveries_sum_total_ttc' : $this->sort;
            $query->orderBy($sortColumn, $this->sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        $clients = $query->paginate(10);

        return view('livewire.commercial.client-index', compact('clients'));
    }
}
