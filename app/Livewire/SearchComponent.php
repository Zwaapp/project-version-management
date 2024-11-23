<?php

namespace App\Livewire;

use Livewire\Component;

class SearchComponent extends Component
{
    protected $queryString = [
        'search' => ['except' => '']
    ];

    public $search = '';

    public function updatedSearch()
    {
        $this->dispatch('searchUpdated', $this->search);
    }

    public function render()
    {
        return view('livewire.search-component');
    }
}
