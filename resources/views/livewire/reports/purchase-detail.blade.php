<div>
    <div wire:ignore.self class="modal fade" id="modalSaleDetail" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info p-1">
                    <h5 class="modal-title">Detalles de la compra #{{$purchase_id}}</h5>
                    <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @if(count($details) >0)
                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover" id="tblPermissions">
                            <thead class="thead-primary">
                                <tr class="text-center">
                                    <th>Folio</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                    <th>Costo</th>
                                    <th>Importe</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($details as $detail)
                                <tr class="text-center">
                                    <td>{{$detail->id }}</td>
                                    <td>{{$detail->product->name }}</td>
                                    <td>{{$detail->quantity}}</td>
                                    <td>${{ $detail->cost }}</td>
                                    <td>${{ round($detail->cost * $detail->quantity,2) }}</td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Sin detalles</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><b>Totales</b></td>
                                    <td class="text-center">{{$details->sum('quantity')}}</td>
                                    <td></td>
                                    <td class="text-center">
                                        @php
                                        $sumTotalDetail = $details->sum(function($item){
                                        return $item->quantity * $item->cost;
                                        });
                                        @endphp
                                        ${{ round($sumTotalDetail,2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @endif

                </div>

                <div class="modal-footer">
                    <button class="btn btn-dark " type="button" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>
</div>