<div>
    <div class="row">

        <div class="col-md-8">
            <div class="card height-equal">
                <div class="card-header border-l-primary border-2">
                    <div class="row">
                        <div class="col-sm-12 col-md-8">
                            <h4>Usuarios</h4>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            {{-- search --}}
                            <div class="job-filter mb-2">
                                <div class="faq-form">
                                    <input wire:model.live='search' class="form-control" type="text"
                                        placeholder="Buscar.."><i class="search-icon" data-feather="search"></i>
                                </div>
                            </div>
                        </div>
                        <div class="contact-edit chat-alert" wire:click='Add'><i class="icon-plus"></i></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover">
                            <thead class="thead-primary">
                                <tr>
                                    <th width="25%">Usuario</th>
                                    <th width="40%">Email</th>
                                    <th width="25%">Estatus</th>
                                    <th width="10%">Role</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $objUser)
                                <tr>
                                    <td> {{$objUser->name }}</td>
                                    <td>{{$objUser->email }}</td>
                                    <td>{{$objUser->status == 'Active' ? 'Activo' : 'Bloqueado' }}</td>
                                    <td>{{$objUser->profile }}</td>
                                    <td class="text-center">


                                        <div class="btn-group btn-group-pill" role="group">
                                            <button class="btn btn-light btn-sm"
                                                wire:click="Edit({{ $objUser->id }})"><i class="fa fa-edit fa-2x"></i>

                                            </button>

                                            <button class="btn btn-light btn-sm" wire:click="Destroy({{$objUser->id}})"
                                                wire:confirm="¿CONFIRMAS ELIMINAR EL USUARIO?" {{
                                                $objUser->sales->count()
                                                == 0 ? '' : 'disabled' }}>
                                                <i class="fa fa-trash fa-2x"></i>
                                            </button>

                                        </div>

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3">Sin resultados</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer p-1">
                    {{$users->links()}}
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-absolute">
                <div class="card-header bg-primary">
                    <h5 class="txt-light">{{ $editing ? 'Editar Usuario' : 'Crear Usuario' }}</h5>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <span>Nombre <span class="txt-danger">*</span></span>
                        <input wire:model="user.name" id='inputFocus' type="text" class="form-control form-control-lg"
                            placeholder="nombre">
                        @error('user.name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mt-3">
                        <span class="form-label">Email <span class="txt-danger">*</span></span>
                        <input wire:model="user.email" class="form-control" type="text">
                        @error('user.email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mt-3">
                        <span>Password <span class="txt-danger">*</span></span>
                        <input wire:model="pwd" type="password" class="form-control form-control-lg"
                            placeholder="password">
                        @error('pwd') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mt-3">
                        <span>Perfil <span class="txt-danger">*</span></span>
                        <select class="form-control" wire:model="user.profile">
                            <option value="0" selected disabled>Seleccionar</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Recepcionista">Recepcionista</option>
                            <option value="Tecnico">Técnico</option>
                            <option value="Otro">Otro</option>
                        </select>
                        @error('user.profile') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>



                </div>
                <div class="card-footer d-flex justify-content-between">
                    <button class="btn btn-light  hidden {{$editing ? 'd-block' : 'd-none' }}"
                        wire:click="cancelEdit">Cancelar
                    </button>

                    <button class="btn btn-info  save" wire:click.prevent="Store">Guardar</button>
                </div>
            </div>
        </div>

    </div>
    @push('my-scripts')

    <script>
        document.addEventListener('livewire:init', () => {   
               
               Livewire.on('init-new', (event) => {
                  document.getElementById('inputFocus').focus()
                })

                })
                    
    </script>

    @endpush

</div>