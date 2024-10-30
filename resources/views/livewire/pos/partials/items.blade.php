<div class="card">
    <div class="card-header">
        <div class="row">

            {{-- buscador por codigo --}}
            @if($typeSearch == 'single')
            <div class="col-sm-12 col-md-6">
                <div class="faq-form">
                    <input wire:keydown.enter='ScanningCode($event.target.value)' class="form-control form-control-lg"
                        type="text" placeholder="Escanea el SKU o Código de Barras [F1]" id="inputSearch">
                    <i class="search-icon" data-feather="search"></i>
                </div>
            </div>
            @else
            {{-- buscador avanzado --}}
            <div class="col-sm-12 col-md-6">
                <div x-data="{ showResults: false }" @click.away="showResults = false; $wire.hideResults()"
                    class="position-relative">
                    <div class="input-group w-100">
                        <input type="text" wire:model.live.debounce.250ms="search" class="form-control form-control-lg"
                            placeholder="Ingresa nombre / código del producto"
                            style="text-transform:capitalize; background-color: rgb(252, 252, 229)" autocomplete="off"
                            @focus="showResults = true" id="inputSearchAdvance">
                    </div>


                    @if(!empty($products))
                    <ul class="list-group mt-0 position-absolute w-100 bg-white border" x-show="showResults"
                        style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                        @foreach($products as $product)
                        <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-2"
                            wire:click="AddProduct_FromAdvancedSearch({{ $product->id }}); showResults = false;"
                            style="cursor: pointer;">
                            <div>
                                <h6 class="mb-0 text-primary">{{ Str::limit($product->name, 25) }}
                                    <small class="mb-0 text-muted">${{ intval($product->price) }}</small>
                                    <small class="mb-0 text-muted"> / <span class="text-info"><i
                                                class="icofont icofont-barcode text-dark"></i>{{ $product->sku }}
                                        </span></small>
                                </h6>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            @endif

            <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                <div class="btn-group btn-group-pill " role="group" aria-label="Basic example">

                    @php
                    $uniqueKey = uniqid();
                    @endphp

                    <livewire:partial-payment :key="$uniqueKey" />

                    <button @if($totalCart>0)
                        onclick="cancelSale()"
                        @endif
                        type="button" class="btn btn-outline-light-2x txt-dark"><i class="icon-trash"></i>
                        Cancelar</button>
                    <button onclick="initPartialPay()" type="button" class="btn btn-outline-light-2x txt-dark"><i
                            class="icon-money"></i>
                        Abonos</button>
                    <button wire:click.prevent="printLast" type="button" class="btn btn-outline-light-2x txt-dark"><i
                            class="icon-printer"></i>
                        Última</button>
                </div>

            </div>
        </div>
    </div>
    <div class="card-body">
        {{-- @json($cart) --}}
        <div class="row">
            <div class="order-history table-responsive wishlist">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            {{-- <th class="p-2" width="100"></th> --}}
                            <th class="p-2">Descripción</th>
                            <th class="p-2" width="200">Precio Vta</th>
                            <th class="p-2" width="300">Cantidad</th>
                            <th class="p-2">Importe</th>
                            <th class="p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cart as $item)
                        <tr>
                            {{-- <td>
                                <img class="img-fluid img-40" src="{{ asset($item['image']) }} ">
                            </td> --}}
                            <td>
                                <div class="product-name txt-info">{{ strtoupper($item['name']) }}</div>
                                {{-- <small class="{{ $item['sku'] == null ? 'd-none' : '' }}">sku:{{ $item['sku'] }}</small> --}}
                            </td>
                            <td>
                                @if(count($item['pricelist']) == 0)
                                <input
                                    wire:keydown.enter.prevent="setCustomPrice('{{ $item['id'] }}', $event.target.value )"
                                    type="text" oninput="justNumber(this)" class="form-control text-center"
                                    value="{{$item['sale_price']}}">

                                @else

                                <div class="mb-3">
                                    <div class="position-relative">
                                        <input class="form-control" id="inputPrice{{$item['id']}}"
                                            wire:keydown.enter.prevent="setCustomPrice('{{ $item['id'] }}', $event.target.value )"
                                            oninput="justNumber(this)" type="text"
                                            placeholder="{{ $item['sale_price'] }}">
                                        <select class="form-select crypto-select warning"
                                            wire:change="setCustomPrice('{{ $item['id'] }}', $event.target.value )">
                                            @foreach($item['pricelist'] as $price)
                                            <option>${{ $price['price'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                @endif
                            </td>
                            <td>
                                <div class="right-details">
                                    <div class="touchspin-wrapper">

                                        <button onclick="updateQty({{ $item['pid'] }},'{{ $item['id'] }}','decrement')"
                                            class="decrement-touchspin btn-touchspin"><i
                                                class="fa fa-minus text-gray"></i>
                                        </button>
                                        <input
                                            wire:keydown.enter.prevent="updateQty('{{ $item['id'] }}', $event.target.value )"
                                            class=" input-touchspin" type="number" value="{{ $item['qty'] }}"
                                            id="p{{$item['pid']}}">

                                        <button onclick="updateQty({{ $item['pid'] }},'{{ $item['id'] }}', 'increment')"
                                            class="increment-touchspin btn-touchspin"><i
                                                class="fa fa-plus text-gray"></i>
                                        </button>
                                    </div>
                                </div>


                            </td>
                            <td>${{ $item['total'] }}</td>
                            <td>

                                <button wire:click.prevent="removeItem({{ $item['pid'] }})"
                                    class="btn btn-light btn-sm">
                                    <i class="fa fa-trash fa-2x"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Agrega productos al carrito</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
</div>
