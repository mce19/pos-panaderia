<?php

namespace App\Traits;

use App\Models\Configuration;

trait UtilTrait
{


    public function validaRut($rut)
    {
        if (!preg_match("/^[0-9.]+[-]?+[0-9kK]{1}/", $rut)) {
            return false;
        }

        $rut = preg_replace('/[\.\-]/i', '', $rut);
        $dv = substr($rut, -1);
        $numero = substr($rut, 0, strlen($rut) - 1);
        $i = 2;
        $suma = 0;

        foreach (array_reverse(str_split($numero)) as $v) {
            if ($i == 8)
                $i = 2;
            $suma += $v * $i;
            ++$i;
        }
        $dvr = 11 - ($suma % 11);

        if ($dvr == 11)
            $dvr = 0;
        if ($dvr == 10)
            $dvr = 'K';
        if ($dvr == strtoupper($dv))
            return true;
        else
            return false;
    }



    function validEmail($str)
    {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }


    function validaProp($value)
    {
        return !empty($value) && trim($value) !== '';
    }

    function zeroFormat($number)
    {
        if (!empty($number)) {
            return str_pad($number, 2, '0', STR_PAD_LEFT);
        } else {
            return null;
        }
    }

    function desgloseMonto($monto)
    {
        if (!empty($monto) && is_numeric($monto)) {

            $config = Configuration::first();

            $iva = ($config->vat / 100);
            $subtotal = ($monto / (1 + $iva));

            return array('iva' => round($subtotal * $iva), 'subtotal' => round($subtotal));
        }

        return array('iva' => 0, 'subtotal' => 0);
    }


    //Cálculo de precio de venta utilizando el método de: "fijación de precios basado en costos y margen de ganancia"
    public function getPrecioVenta($costoOriginal, $cantidadOriginal, $costoAdicional, $cantidadAdicional, $porcentajeGanancia)
    {

        // Validar que los valores proporcionados sean numéricos y no negativos
        if (
            !is_numeric($costoOriginal) || !is_numeric($cantidadOriginal) || !is_numeric($costoAdicional) || !is_numeric($cantidadAdicional) || !is_numeric($porcentajeGanancia) ||
            $costoOriginal < 0 || $cantidadOriginal < 0 || $costoAdicional < 0 || $cantidadAdicional < 0 || $porcentajeGanancia < 0
        ) {
            return array("error" => "Los datos proporcionados deben ser numéricos y no negativos", "price" => 0);
        }

        // Validar que las cantidades no sean cero
        if ($cantidadOriginal == 0 || $cantidadAdicional == 0) {
            return array("error" => "Las cantidades no pueden ser cero", "price" => 0);
        }

        // Calcular el costo total de todos los productos
        $costoTotalOriginal = $costoOriginal * $cantidadOriginal;
        $costoTotalAdicional = $costoAdicional * $cantidadAdicional;
        $costoTotal = $costoTotalOriginal + $costoTotalAdicional;
        $stockTotal = $cantidadOriginal + $cantidadAdicional;

        // Calcular la ganancia total esperada
        $gananciaTotal = $costoTotal * ($porcentajeGanancia / 100);

        // Calcular el incremento de precio por unidad
        $incrementoPorUnidad = $gananciaTotal / ($cantidadOriginal + $cantidadAdicional);

        // Calcular el nuevo precio de venta por unidad
        //$precioVentaActual = $costoTotalOriginal / $cantidadOriginal;
        $precioVentaActual = $costoTotal / $stockTotal;
        $nuevoPrecioVenta = $precioVentaActual + $incrementoPorUnidad;

        return array("price" => $nuevoPrecioVenta);
    }
}
