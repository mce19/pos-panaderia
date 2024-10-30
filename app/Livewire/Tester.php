<?php

namespace App\Livewire;

use Livewire\Component;

class Tester extends Component
{
    function mount()
    {
        session(['map' => 'Tester', 'child' => ' Componente ']);
    }
    public function render()
    {
        return view('livewire.tester');
    }
}
