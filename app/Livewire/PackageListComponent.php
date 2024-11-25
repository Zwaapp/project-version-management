<?php

namespace App\Livewire;

use App\Models\Package;
use Livewire\Attributes\On;
use Livewire\Component;

class PackageListComponent extends Component
{
    public string $search = '';

    public $packages = [];

    public function mount()
    {
        $this->search = request()->get('search', '');

        $this->fetchPackages();
    }

    public function render()
    {
        return view('livewire.package-list-component');
    }

    #[On('searchUpdated')]
    public function searchUpdated(string $search)
    {
        $this->search = $search;

        $this->fetchPackages();
    }

    public function fetchPackages()
    {
        if(!$this->search) {
            $this->packages = [];
            return;
        }

        $this->packages = Package::search($this->search)->orderBy('name')->get();
    }
}
