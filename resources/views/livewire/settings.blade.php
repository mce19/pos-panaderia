<div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-absolute">
                <div class="card-header bg-primary">
                    <h5 class="txt-light">Configuraciones Generales</h5>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="form-group col-sm-12 col-md-6">
                            <span>EMPRESA<span class="txt-danger">*</span></span>
                            <input wire:model="businessName" id='inputFocus' type="text"
                                class="form-control text-purple" placeholder="nombre" maxlength="150">
                            @error('businessName') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>


                        <div class="form-group col-sm-12 col-md-6">
                            <span>TELÉFONO <span class="txt-danger"></span></span>
                            <input wire:model="phone" type="text" class="form-control text-purple " placeholder=""
                                maxlength="20">
                            @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="form-group col-sm-12 col-md-6">
                            <span>RFC / RUT <span class="txt-danger">*</span></span>
                            <input wire:model="taxpayerId" type="text" class="form-control text-purple " placeholder=""
                                maxlength="35">
                            @error('taxpayerId') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <span>IVA / VAT <span class="txt-danger">*</span></span>
                            <input wire:model="vat" type="text" class="form-control text-purple " placeholder="">
                            @error('vat') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="form-group col-sm-12 col-md-6">
                            <span>IMPRESORA<span class="txt-danger">*</span></span>
                            <input wire:model="printerName" type="text" class="form-control text-purple " placeholder=""
                                maxlength="55">
                            @error('printerName') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>



                        <div class="form-group col-sm-12 col-md-6">
                            <span>VENTAS CRÉDITO (DÍAS)<span class="txt-danger">*</span></span>
                            <input wire:model="creditDays" type="number" class="form-control text-purple "
                                placeholder="">
                            @error('creditDays') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="form-group col-sm-12 col-md-6">
                            <span>WEBSITE<span class="txt-danger"></span></span>
                            <input wire:model="website" type="text" class="form-control text-purple "
                                placeholder="www.website.com" maxlength="99">
                            @error('website') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <span>LEYENDA<span class="txt-danger"></span></span>
                            <input wire:model="leyend" type="text" class="form-control text-purple" placeholder=""
                                maxlength="99">
                            @error('leyend') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="form-group col-sm-12 ">
                            <span class="form-label">DIRECCIÓN <span class="txt-danger"></span></span>
                            <input wire:model="address" class="form-control text-purple" type="text" maxlength="255">
                            @error('address')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                </div>

                <div class="card-footer p-2">

                    <button class="btn btn-info" wire:click.prevent="saveConfig" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="saveConfig">
                            Guardar
                        </span>
                        <span wire:loading wire:target="saveConfig">
                            Registrando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>