<?php

namespace App\Livewire\Admin;

use App\Models\Client;
use App\Models\Region;
use Livewire\Component;
use Livewire\WithPagination;

class ClientIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $region_id = '';
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
        $this->region_id = '';
        $this->sort = 'id';
        $this->sortDirection = 'asc';
    }

    public function render()
    {
        $query = Client::with('region')
            ->withSum(['deliveries' => function ($q) {
                $q->where('deliveries.status', 'livrer');
            }], 'total_ttc');

        if ($this->search !== '') {
            $query->where(function($q) {
                $q->where('company_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('id', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->region_id !== '') {
            $query->where('region_id', $this->region_id);
        }

        if ($this->sort) {
            if ($this->sort === 'region_name') {
                 $query->join('regions', 'clients.region_id', '=', 'regions.id')
                       ->select('clients.*')
                       ->orderBy('regions.name', $this->sortDirection);
            } else {
                 $sortColumn = $this->sort === 'total_ttc' ? 'deliveries_sum_total_ttc' : $this->sort;
                 $query->orderBy($sortColumn, $this->sortDirection);
            }
        }

        $clients = $query->paginate(10);
        $regions = Region::all();

        return view('livewire.admin.client-index', compact('clients', 'regions'));
    }
}
