<div>
    <div class="row">
        <div>
            @include('livewire.purchases.partials.payConfirm')
        </div>
        <div class="col-sm-12 col-md-3">
            <div class="card customer-sticky">
                <div class="card-header card-no-border pb-3">
                    <div class="header-top border-bottom">
                        <h5 class="m-0">Resumen </h5>
                    </div>
                </div>
                <div class="card-body pt-0 order-details">
                    <div class="input-group">
                        <span class="input-group-text b-0">Flete $</span>
                        <input wire:keydown.enter.prevent='setFlete($event.target.value)' class="form-control"
                            id="inputFlete" type="text" placeholder="Costo del flete" @if($flete>0 )
                        disabled
                        value = {{$flete}}
                        @endif
                        >
                        @if($flete !=0)
                        <span wire:click='unsetFlete' class="input-group-text" style="cursor:pointer">
                            <i class="icofont icofont-trash"></i>
                        </span>
                        @endif
                    </div>

                    <div class="total-item">
                        <div class="item-number"><span class="text-gray">Artículos</span><span class="f-w-500">{{
                                $itemsCart }}
                                (Items)</span></div>
                        <div class="item-number"><span class="text-gray">Subtotal</span><span class="f-w-500">${{
                                $subtotalCart }}</span></div>
                        <div class="item-number border-bottom"><span class="text-gray">I.V.A.</span><span
                                class="f-w-500">${{$ivaCart}}</span>
                        </div>
                        <div class="item-number"><span class="text-gray">Flete</span><span class="f-w-500 text-success">
                                ${{ $flete }}
                            </span>
                        </div>
                        <div class="item-number pt-3 pb-0"><span class="f-w-700">TOTAL</span>
                            <h6 class="txt-primary">${{ $totalCart }}</h6>
                        </div>
                    </div>
                    <h5 class="m-0 p-t-40 text-center">Método de Pago</h5>
                    <div class="payment-methods">
                        <div wire:click="initPayment(1)">
                            <div class="bg-payment widget-hover"> <img
                                    src="../assets/images/dashboard-8/payment-option/cash.svg" alt="cash"></div><span
                                class="f-w-500 text-gray">Contado</span>
                        </div>
                        <div wire:click="initPayment(2)">
                            <div class="bg-payment widget-hover"> <img
                                    src="../assets/images/dashboard-8/payment-option/card.svg" alt="card"></div><span
                                class="f-w-500 text-gray">Crédito</span>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-9">
            @include('livewire.purchases.partials.items')
        </div>
    </div>


    @include('livewire.purchases.partials.script')




</div>