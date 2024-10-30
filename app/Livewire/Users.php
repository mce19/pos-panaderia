<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;


class Users extends Component
{
    use WithPagination;

    public User $user;
    public $user_id, $editing, $search, $records, $pagination = 5, $pwd,  $temppwd;

    protected $rules =
    [
        'user.name' => "required|max:85|unique:users,name",
        'user.email' => 'required|email|max:75',
        'user.password' => 'nullable',
        'user.profile' => 'required|in:Administrador,Recepcionista,Tecnico,Otro',
        'user.status' => 'required|in:Active,Locked',
    ];

    protected $messages = [
        'user.name.required' => 'Nombre requerido',
        'user.name.max' => 'Nombre debe tener máximo 85 caracteres',
        'user.profile.required' => 'Selecciona el perfil',
        'user.name.unique' => 'El nombre ya existe',
        'user.email.required' => 'Email requerido',
        'user.email.email' => 'Email inválido',
        'user.email.max' => 'Email debe tener máximo 75 caracteres',
        'user.status.required' => 'Estatus requerido',
        'user.status.in' => 'Elige un estatus',
        'user.profile.in' => 'Elige un perfil'
    ];


    public function mount()
    {
        $this->user = new User();
        $this->user->status = 'Active';
        $this->user->profile = 0;
        $this->editing = false;

        session(['map' => 'Usuarios', 'child' => ' Componente ']);
    }


    public function render()
    {
        return view('livewire.users.users', [
            'users' => $this->loadUsers()
        ]);
    }


    public function loadUsers()
    {
        if (!empty($this->search)) {

            $this->resetPage();

            $query = User::with('sales')->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orderBy('name', 'asc');
        } else {
            $query =  User::with('sales')->orderBy('name', 'asc');
        }

        $this->records = $query->count();

        return $query->paginate($this->pagination);
    }


    public function Add()
    {
        $this->resetValidation();
        $this->resetExcept('user');
        $this->user = new User();
        $this->editing = false;
        $this->dispatch('init-new');
    }

    public function Edit(User $user)
    {
        $this->resetValidation();
        $this->user = $user;
        $this->editing = true;
        $this->temppwd = $user->password;
        $this->pwd = null;
    }

    public function cancelEdit()
    {
        $this->resetValidation();
        $this->user = new User();
        $this->editing = false;
        $this->search = null;
        $this->dispatch('init-new');
    }



    public function Store()
    {

        $this->rules['user.name'] = $this->user->id > 0 ? "required|max:85|unique:users,name,{$this->user->id}" : 'required|max:85|unique:users,name';



        $this->validate($this->rules, $this->messages);


        if ($this->user->id == null) {
            if (empty($this->pwd)) {
                $this->addError('pwd', 'Ingresa el password');
                return;
            } else {
                $this->user->password = bcrypt($this->pwd);
            }
        } else {
            if (!empty($this->pwd))
                $this->user->password = bcrypt($this->pwd);
            else
                $this->user->password = $this->temppwd;
        }


        // save model
        $this->user->save();

        $this->dispatch('noty', msg: $this->user->id != null ? 'USUARIO ACTUALIZADO CORRECTAMENTE' : 'USUARIO REGISTRADO CON ÉXITO');
        $this->resetExcept('user');
        $this->user = new User();
        $this->user->status = 'Active';
        $this->user->profile = 0;
    }


    public function Destroy(User $user)
    {
        if ($user->sales->count() > 0) {
            $this->dispatch('noty', msg: 'EL USUARIO TIENE VENTAS RELACIONADAS, NO ES POSIBLE ELIMINARLO');
            return;
        }
        if ($user->purchases()->count() > 0) {
            $this->dispatch('noty', msg: 'EL USUARIO TIENE COMPRAS RELACIONADAS, NO ES POSIBLE ELIMINARLO');
            return;
        }

        $user->delete();

        $this->resetPage();


        $this->dispatch('noty', msg: 'USUARIO ELIMINADO CON ÉXITO');
    }
}
