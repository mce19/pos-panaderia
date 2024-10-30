<div wire:ignore.self class="modal fade" id="modalProduct" tabindex="-1" role="dialog" aria-labelledby="tooltipmodal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $btnCreateCategory ? 'Crear Categoría' : 'Crear Proveedor' }}</h5>
                <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" wire:model.live='catalogueName' class="form-control"
                        maxlength="{{ $btnCreateCategory ? 45 : 55 }}"
                        placeholder="{{ $btnCreateCategory ? 'Nombre de la categoría' : 'Nombre del proveedor' }}">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary" wire:click.prevent='createCatalogue' type="button"
                    {{$catalogueName==null ? 'disabled' : '' }}>Guardar</button>
            </div>
        </div>
    </div>
</div>

{{-- <div class="modal fade" id="modalProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-toggle-wrapper">
                    <h4>Up to <strong class="txt-danger">85% OFF</strong>, Hurry Up Online Shopping</h4>
                    <div class="modal-img"> <img src="../assets/images/gif/online-shopping.gif" alt="online-shopping">
                    </div>
                    <p class="text-sm-center">Our difficulty in finding regular clothes that was of great quality,
                        comfortable, and didn't impact the environment given way to Creatures of Habit.</p>
                    <button class="btn bg-primary d-flex align-items-center gap-2 text-light ms-auto" type="button"
                        data-bs-dismiss="modal">Explore More<i data-feather="arrow-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</div> --}}