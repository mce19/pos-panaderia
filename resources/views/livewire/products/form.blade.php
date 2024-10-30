<div class="card">
    <div class="card-header">
        <div>
            @if($editing && $form->product_id > 0)
            <h5>Editar Producto | <small class="text-info">{{$form->name}}</small></h5>
            @else
            <h5>Crear Nuevo Producto</h5>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="sidebar-body">
            <form class="row g-2">

                    {{-- Galería de Imágenes --}}
                    <div class="col-sm-12">
                        <label class="form-label">Galería de Imágenes</label>
                        <input type="file" class="form-control" wire:model="form.gallery" accept="image/x-png,image/jpeg"
                            style="height:44px" multiple id="inputImg">
                        {{-- @error('gallery.*')
                        <span style="color: red;">{{ $message }}</span>
                        @enderror --}}
                        <div>
                            <div wire:loading wire:target="form.gallery">Cargando imágenes...</div>
                            @if (!empty($form->gallery))
                            <div class="row">
                                @foreach ($form->gallery as $photo)
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                                    <div class="media">
                                        <img src="{{ $photo->temporaryUrl() }}" class="img-fluid rounded" alt="img"
                                            width="40%">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                {{-- Nombre --}}
                <div class="col-sm-12 col-md-8 mt-2 mt-sm-4">
                    <label class="form-label">Nombre <span class="txt-danger">*</span></label>
                    <input wire:model="form.name" class="form-control" type="text">
                    @error('form.name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- SKU   AQUI--}}

                @include('livewire.products.partials.sku')


                {{-- Descripción --}}
                <div class="col-sm-12 mb-3 mt-2 mt-sm-4">
                    <label class="form-label">Descripción</label>
                    <div class="toolbar-box" wire:ignore>
                        <div id="toolbar2"><span class="ql-formats">
                                <select class="ql-size"></select></span><span class="ql-formats">
                                <button class="ql-bold">Bold </button>
                                <button class="ql-italic">Italic </button>
                                <button class="ql-underline">underline</button>
                                <button class="ql-strike">Strike </button></span><span class="ql-formats">
                                <button class="ql-list" value="ordered">List </button>
                                <button class="ql-list" value="bullet"> </button>
                                <button class="ql-indent" value="-1"> </button>
                                <button class="ql-indent" value="+1"></button></span><span
                                class="ql-formats">
                                <button class="ql-link"></button>
                                <button class="ql-image"></button>
                                <button class="ql-video"></button></span></div>
                        <div id="editor2"></div>
                    </div>
                </div>
                {{-- Tipo --}}
                <div class="col-sm-12 col-md-3 mt-2 mt-sm-4">
                    <label class="form-label">Tipo <span class="txt-danger">*</span></label>
                    <select wire:model="form.type" class="form-select" required="">
                        <option value="service">Servicio</option>
                        <option value="physical">Producto Físico</option>
                    </select>
                </div>
                {{-- Estatus --}}
                <div class="col-sm-12 col-md-3 mt-2 mt-sm-4">
                    <label class="form-label">Estatus <span class="txt-danger">*</span></label>
                    <select wire:model="form.status" class="form-select" required="">
                        <option value="available" selected>Disponible</option>
                        <option value="out_of_stock">Sin Stock</option>
                    </select>
                </div>
                {{-- Costo de Compra --}}
                <div class="col-sm-12 col-md-3 mt-2 mt-sm-4">
                    <label class="form-label">Costo de Compra</label>
                    <input wire:model="form.cost" class="form-control numerico" type="number">
                </div>
                {{-- Precio de Venta --}}
                <div class="col-sm-12 col-md-3 mt-2 mt-sm-4">
                    <label class="form-label">Precio de Venta</label>
                    <input wire:model="form.price" class="form-control numerico" type="number">
                </div>
                {{-- Administrar Stock --}}
                <div class="col-sm-12 col-md-3 mt-2 mt-sm-4">
                    <label class="form-label">Administrar Stock <span class="txt-danger">*</span></label>
                    <select wire:model="form.manage_stock" class="form-select">
                        <option value="1" selected>Si, Controlar Stock</option>
                        <option value="0">Vender sin Límites</option>
                    </select>
                </div>
                {{-- Stock Actual --}}
                <div class="col-sm-12 col-md-3 mt-2 mt-sm-4">
                    <label class="form-label">Stock Actual <span class="txt-danger">*</span></label>
                    <input wire:model="form.stock_qty" class="form-control numerico" type="number">
                </div>
                {{-- Stock de Alerta --}}
                <div class="col-sm-12 col-md-3 mt-2 mt-sm-4">
                    <label class="form-label">Stock de Alerta <span class="txt-danger">*</span></label>
                    <input wire:model="form.low_stock" class="form-control numerico" type="number">
                </div>
                {{-- Categoría --}}
                <div class="col-sm-6 mt-2 mt-sm-4">
                    <label class="form-label">Categoría <span class="txt-danger">*</span></label>
                    <select wire:model="form.category_id" class="form-select">
                        <option value="0" disabled> Seleccionar</option>
                        @foreach ($categories as $category)
                        <option value="{{$category->id}}" {{$category->id == $form->category_id ? 'selected' : '' }}>
                            {{$category->name}}
                        </option>
                        @endforeach
                    </select>
                    @error('form.category_id')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                {{-- Proveedor --}}
                <div class="col-sm-6 mt-2 mt-sm-4">
                    <label class="form-label">Proveedor <span class="txt-danger">*</span></label>
                    <select wire:model="form.supplier_id" class="form-select" id="supplier">
                        <option value="0" disabled> Seleccionar</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{$supplier->id}}" {{$supplier->id == $form->supplier_id ? 'selected' : '' }}>
                            {{$supplier->name}}
                        </option>
                        @endforeach
                    </select>
                    @error('form.supplier_id')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                {{-- Precios --}}
                {{-- <div class="col-sm-12">
                    <label class="form-label">Precios</label>
                    <div class="price-wrapper">
                        <div class="row g-3 custom-input">
                            <div class="col-sm-3">
                                <label class="form-label" for="initialCost">Precio de Venta <span
                                        class="txt-danger">*</span></label>
                                <input wire:model="form.value" class="form-control numerico" type="number">
                            </div>
                            <div class="col-sm-12 col-md-2">
                                <button wire:click.prevent="storeTempPrice" class="btn btn-primary mt-4">Agregar</button>
                            </div>
                        </div>
                        <div class="row g-3 mt-3">
                            <div class="col-sm-12 col-md-4">
                                <table class="table table-light">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Precio</th>
                                            <th class="text-right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($form->values as $item)
                                        <tr>
                                            <td>${{ $item['price'] }}</td>
                                            <td>
                                                <button class="btn btn-light btn-sm"
                                                    wire:click.prevent="removeTempPrice('{{ $item['id'] }}')">
                                                    <i class="fa fa-trash fa-2x"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </form>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <button wire:click.prevent="cancel" class="btn btn-light">
            Cancelar
 </button>

        @if($editing && $form->product_id == 0)
        <button wire:click.prevent="Store" class="btn btn-warning">
            Registrar Producto
        </button>
        @else
        <button wire:click.prevent="Update" class="btn btn-dark">
            Actualizar Producto
        </button>
        @endif
    </div>
</div>
