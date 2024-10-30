<div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-absolute">
                <div class="card-header bg-primary">
                    <h5 class="txt-light">Title</h5>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label>Name</label>
                        <input wire:model.defer="category.name" id='inputFocus' type="text"
                            class="form-control form-control-lg" placeholder="Name">
                        @error('category.name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>






                </div>
                <div class="card-footer d-flex justify-content-between">
                    <button class="btn btn-light  hidden {{$editing ? 'd-block' : 'd-none' }}"
                        wire:click="cancelEdit">Cancelar
                    </button>

                    <button class="btn btn-info  save" wire:click="Store">Guardar</button>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card height-equal">
                <div class="card-header border-l-primary border-2">
                    <h4>Categorías</h4>
                    <p class="mt-1 f-m-light">Listado registrado</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover  text-center">
                            <thead class="thead-primary">
                                <tr>
                                    <th class="text-center">Image</th>
                                    <th width="60%">Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            links
                        </div>
                        <div class="col-md-6"><span class="float-right">Records:</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>