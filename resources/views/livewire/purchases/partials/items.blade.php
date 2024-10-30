<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-sm-12 col-md-5">
                @if($supplier != null)
                <span> {{ $supplier['name'] }} <i class="icofont icofont-verification-check"></i></span>
                @else
                Selecciona el proveedor
                @endif
                <div class="input-group" wire:ignore>
                    <input class="form-control" type="text" id="inputSupplier" placeholder="F1">
                    <span class="input-group-text list-light">
                        <i class="search-icon" data-feather="user"></i>
                    </span>
                </div>



            </div>
            <div class="col-sm-12 col-md-7 ">
                <livewire:product-search>



                    {{-- <button @if($totalCart>0)
                        onclick="cancelSale()"
                        @endif
                        type="button" class="btn btn-outline-light-2x txt-dark"><i class="icon-trash"></i>
                        Cancelar</button>
                    <button onclick="initPartialPay()" type="button" class="btn btn-outline-light-2x txt-dark"><i
                            class="icon-money"></i>
                        Abonos</button>
                    <button wire:click.prevent="printLast" type="button" class="btn btn-outline-light-2x txt-dark"><i
                            class="icon-printer"></i>
                        Última</button> --}}


            </div>
        </div>
    </div>
    <div class="card-body">
        {{-- @json($cart) --}}
        <div class="row">
            <div class="order-history table-responsive wishlist">
                <table class="table table-bordered" id="tblProducts">
                    <thead>
                        <tr>
                            {{-- <th class="p-2" width="100"></th> --}}
                            <th class="p-2">Descripción</th>
                            <th class="p-2" width="300">Cantidad</th>
                            <th class="p-2" width="200">Costo</th>
                            <th class="p-2">Importe</th>
                            @if($flete >0)
                            <th class="p-2">Flete</th>
                            <th class="p-2">Total</th>
                            @endif
                            <th class="p-2"></th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse($cart as $item)
                        <tr>
                            <td>
                                <div class="product-name txt-info">{{ strtoupper($item['name']) }}</div>

                            </td>


                            <td>
                                <div class="right-details">
                                    <div class="touchspin-wrapper">
                                        <button wire:click="IncDec('{{ $item['id'] }}', 2)"
                                            class="decrement-touchspin btn-touchspin"><i
                                                class="fa fa-minus text-gray"></i></button>
                                        <input
                                            wire:keydown.enter.prevent="updateQty('{{ $item['id'] }}', $event.target.value )"
                                            class=" input-touchspin" type="number" value="{{ $item['qty'] }}"
                                            data-qty="{{ $item['qty'] }}" id="p{{$item['id']}}">

                                        <button wire:click="IncDec('{{ $item['id'] }}', 1)"
                                            class="increment-touchspin btn-touchspin"><i
                                                class="fa fa-plus text-gray"></i></button>
                                    </div>
                                </div>

                            </td>
                            <td>
                                <input wire:keydown.enter.prevent="setCost('{{ $item['id'] }}', $event.target.value )"
                                    class="form-control text-center" type="number" value="{{ $item['cost'] }}"
                                    id="c{{$item['id']}}">
                            </td>
                            <td>₡{{ $item['total'] }}</td>
                            @if($flete >0)
                            <td>₡{{ $item['flete']['flete_producto'] }}</td>
                            <td>
                                ₡{{ floatval($item['total']) + floatval($item['flete']['total_flete']) }}
                            </td>
                            @endif
                            <td>

                                <button wire:click.prevent="removeItem('{{ $item['id'] }}')"
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
