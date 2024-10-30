<?php

namespace App\Traits;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Configuration;

trait JsonTrait
{

    function jsonData($sale_id)
    {
        // venta - detalle -product - cliente - user 

        $sale = Sale::find($sale_id);

        $detalle = SaleDetail::join('products as p', 'p.id', 'sale_details.product_id')
            ->select('sale_details.*', 'p.name')
            ->where('sale_details.sale_id', $sale_id)
            ->orderBy('p.name')
            ->get();


        $cliente = $sale->customer;

        $user = $sale->user;

        $json =  $sale->toJson() . '|' . $detalle->toJson() . '|' . $cliente->toJson() . '|' . $user->toJson();

        $b64  = base64_encode($json);

        return $b64;
    }


    function jsonData2($sale_id)
    {

        $sale = Sale::select('id', 'user_id', 'customer_id', 'total', 'items', 'status', 'type', 'cash', 'change', 'created_at')
            ->find($sale_id);

        $detalle = $sale->details()->select('product_id', 'quantity', 'sale_price', 'discount')
            ->with('product:id,name')
            ->get();


        $cliente = $sale->customer;
        $user = $sale->user;
        $company = Configuration::first();

        /* $cliente y $user ya teienen la info de cada entidad para imprimir, 
             por tanto eliminamos de memoria la carga de las relaciones usando unsetRelation
        */
        $sale->unsetRelation('user');
        $sale->unsetRelation('customer');

        // medida y tipo de documento
        $size = array('size' => 80, 'type' => 'receipt');




        //array cálculo totales
        $valuesTotales = array('articulos' => $sale->items,  'subtotal' => round($sale->total / 1.16, 2), 'iva' => round((($sale->total / 1.16) * 0.16)), 'total' => $sale->total);
        if (intval($sale->discount) > 0) {
            $valuesTotales['descuento'] = round($sale->discount, 2);
        }


        $json =  $sale->toJson() . '|' . $detalle->toJson() . '|' . $cliente->toJson() . '|' . $user->toJson()
            . '|' . json_encode($size) . '|' . json_encode($company)
            . '|' .  json_encode($valuesTotales)
            . '|null|null|null|null'; // delivery | Timbre | Folio fiscal | Promo



        //comprimir
        $compress = gzcompress($json, 9);

        //base64
        $b64  = base64_encode($compress);

        //devolver json        
        return $b64;
    }


    function jsonData3($sale_id)
    {

        //version ninja
        $sale = Sale::select('id', 'user_id', 'customer_id', 'total', 'items', 'status', 'type', 'cash', 'change')
            ->with(['user' => function ($query) {
                $query->select('id', 'name as user_name');
            }, 'customer' => function ($query) {
                $query->select('id', 'name as customer_name');
            }, 'details' => function ($query) {
                $query->select('sale_id', 'product_id', 'quantity', 'sale_price', 'discount');
            }, 'details.product' => function ($query) {
                $query->select('id', 'name');
            }])
            ->find($sale_id);

        $json =  $sale->toJson();

        $b64  = base64_encode($json);

        return $b64;
    }
}
