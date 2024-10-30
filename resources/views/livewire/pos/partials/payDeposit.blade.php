<div>
    <div wire:ignore.self class="modal fade" id="modalDeposit" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">{{ $payTypeName }}</h5>
                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="light-card balance-card align-items-center mb-1">
                        <h6 class="f-w-400 f-18 mb-0">Artículos:</h6>
                        <div class="ms-auto text-end">
                            <span class="f-18 f-w-700">
                                {{ $itemsCart }}
                            </span>
                        </div>
                    </div>
                    <div class="light-card balance-card align-items-center mb-1">
                        <h6 class="f-w-400 f-18 mb-0">Subtotal:</h6>
                        <div class="ms-auto text-end">
                            <span class="f-18 f-w-700">
                                ₡{{ $subtotalCart }}
                            </span>
                        </div>
                    </div>
                    <div class="light-card balance-card align-items-center border-bottom">
                        <h6 class="f-w-400 f-18 mb-0">I.V.A.:</h6>
                        <div class="ms-auto text-end">
                            <span class="f-18 f-w-700">
                                ₡{{ $ivaCart }}
                            </span>
                        </div>
                    </div>
                    <div class="light-card balance-card align-items-center">
                        <h6 class="f-w-700 f-18 mb-0 txt-success">TOTAL:</h6>
                        <div class="ms-auto text-end">
                            <span class="f-18 f-w-700">
                                ₡{{ $totalCart }}
                            </span>
                        </div>
                    </div>

                    <!-- Información de pago con tarjeta (sin campos de entrada) -->
                    <div class="mt-3">
                        <p class="text-muted">El método de pago se registra una vez confirmado</p>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>

                    <button class="btn btn-primary" wire:click.prevent='Store' type="button"
                        wire:loading.attr="disabled" {{ floatval($totalCart)==0 ? 'disabled' : '' }}>
                        <span wire:loading.remove wire:target="Store">
                            Confirmar Pago
                        </span>
                        <span wire:loading wire:target="Store">
                            Procesando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
