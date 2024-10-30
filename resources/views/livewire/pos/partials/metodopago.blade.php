<div>
    <div class="mt-4">


        <!-- Botones de montos predefinidos -->
        <div class="row">
            <div class=" mb-2">
            <button type="button" class="btn btn-primary mt-2" wire:click="$set('cashAmount', 1000)">₡1000</button>
            <button type="button" class="btn btn-primary mt-2" wire:click="$set('cashAmount', 2000)">₡2000</button>
            <button type="button" class="btn btn-primary mt-2" wire:click="$set('cashAmount', 5000)">₡5000</button>
            <button type="button" class="btn btn-primary mt-2" wire:click="$set('cashAmount', 10000)">₡10000</button>
           </div>
        </div>



        <div class="position-relative">
            <input class="form-control w-100"
                   oninput="validarInputNumber(this)"
                   wire:model.live="cashAmount"
                   type="number"
                   id="inputCash"
                   placeholder="Efectivo">
        </div>



        <div class="light-card balance-card {{ $cashAmount > 0 ? 'd-block' : 'd-none' }} mt-2">
            <h6 class="f-w-400 f-16 mb-0">Cambio: <span class="f-16 txt-warning">${{ $change }}</span></h6>
        </div>
    </div>
</div>
