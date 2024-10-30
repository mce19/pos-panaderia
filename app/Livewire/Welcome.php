<?php

namespace App\Livewire;

use Livewire\Component;
//use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\Permission;

class Welcome extends Component
{
    public function render()
    {
        // $role = Role::find(1);
        // $permissions = Permission::all();
        // $role->syncPermissions($permissions);

        return view('livewire.welcome.page');
    }
}
