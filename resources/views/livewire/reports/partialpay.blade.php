<div>
    <div wire:ignore.self class="modal fade" id="modalPartialPay" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Abono a Cuenta</h5>
                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($sale_id != null)
                    <section>
                        <div class="card height-equal">
                            <div class="card-header border-l-warning border-r-warning border-3 p-2">
                                <h4 class="txt-dark text-center"><i class="icofont icofont-ui-user"></i>
                                    {{ $customer_name }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="light-card balance-card align-items-center mb-1 col-sm-12 col-md-4">
                                        <h6 class="f-w-600 f-18 mb-0 txt-warning">DEBE:</h6>
                                        <div class="ms-auto text-end">
                                            <span class="f-18 f-w-700 ">
                                                ${{ $debt }}
                                            </span>
                                        </div>
                                    </div>
                                    <div
                                        class="light-card balance-card align-items-center mb-1 col-sm-12 col-md-4 m-l-10">
                                        <h6 class="f-w-600 f-18 mb-0 txt-warning">N° Venta:</h6>
                                        <div class="ms-auto text-end">
                                            <span class="f-18 f-w-700 ">
                                                {{ $sale_id }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <div class="input-group mt-4">
                                        <select class="form-select" wire:model="bank">
                                            <option value="0">Seleccionar</option>
                                            @forelse($banks as $bank)
                                            <option value="{{$bank->id}}">{{$bank->name}}</option>
                                            @empty
                                            <option value="-1" disabled>No hay bancos registrados</option>
                                            @endforelse
                                        </select>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="position-relative">
                                                <select class="form-control crypto-select info" disabled>
                                                    <option>N°. CUENTA:</option>
                                                </select>
                                                <input class="form-control" oninput="validarInputNumber(this)"
                                                    wire:model.live="acountNumber" type="text">
                                            </div>
                                            @error('nacount')
                                            <span class="txt-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="position-relative">
                                                <select class="form-control crypto-select info" disabled>
                                                    <option>N°. DEPÓSITO:</option>
                                                </select>
                                                <input class="form-control" oninput="validarInputNumber(this)"
                                                    wire:model.live="depositNumber" type="text">
                                            </div>
                                            @error('ndeposit')
                                            <span class="txt-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="position-relative">
                                            <select class="form-control crypto-select info" disabled>
                                                <option>INGRESA MONTO:</option>
                                            </select>
                                            <input class="form-control" oninput="validarInputNumber(this)"
                                                wire:model="amount" type="text" id="partialPayInput">
                                        </div>
                                        @error('amount')
                                        <span class="txt-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-12 col-md-4">

                                        <button class="btn btn-dark" type="button"
                                            wire:click.prevent="cancelPay">Cancelar</button>

                                        <button class="btn btn-primary" wire:click.prevent='doPayment' type="button"
                                            wire:loading.attr="disabled">

                                            <span wire:loading.remove wire:target="doPayment">
                                                Registrar Pago
                                            </span>
                                            <span wire:loading wire:target="doPayment">
                                                Registrando...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    @endif
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary " type="button" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>