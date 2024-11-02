<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Payment;
use Mike42\Escpos\Printer;
use App\Models\Configuration;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

trait PrintTrait
{
    use UtilTrait;

    function printSale($saleId)
    {

        try {

            $config = Configuration::first();

            if ($config) {

                $sale = Sale::with(['customer', 'user', 'details', 'details.product'])->find($saleId);

                $connector = new WindowsPrintConnector($config->printer_name);
                $printer = new Printer($connector);

                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(2, 2);

                $printer->text(strtoupper($config->business_name) . "\n");
                $printer->setTextSize(1, 1);
                $printer->text("$config->address \n");
                $printer->text("NIT: $config->taxpayer_id \n");
                $printer->text("TEL: $config->phone \n\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                //$printer->text("=============================================\n");
                $printer->text("Folio: " . $sale->id . "\n");
                $printer->text("Fecha: " . Carbon::parse($sale->created_at)->format('d/m/Y h:m:s') . "\n");
                $printer->text("Cajero: " . $sale->user->name . " \n");
                //$printer->text("=============================================\n");



                $maskHead = "%-30s %-5s %-8s";
                $maskRow = $maskHead; //"%-.31s %-4s %-5s";

                $headersName = sprintf($maskHead, 'DESCRIPCION', 'CANT', 'PRECIO');
                $printer->text("=============================================\n");
                $printer->text($headersName . "\n");
                $printer->text("=============================================\n");

                foreach ($sale->details as $item) {

                    $descripcion_1 = $this->cortar($item->product->name, 30);
                    $row_1 = sprintf($maskRow, $descripcion_1[0], $item->quantity, '$' . number_format($item->sale_price, 2));
                    $printer->text($row_1 . "\n");

                    if (isset($descripcion_1[1])) {
                        $row_2 = sprintf($maskRow, $descripcion_1[1], '', '', '');
                        $printer->text($row_2 . "\n");
                    }
                }

                $printer->text("=============================================" . "\n");

                $printer->text("CLIENTE: " . $sale->customer->name  . "\n\n");


                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("NO. DE ARTICULOS $sale->items" . "\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);

                $desglose = $this->desgloseMonto($sale->total);
                $printer->text("SUBTOTAL....... $" . number_format($desglose['subtotal'], 2) . "\n");
                $printer->text("IVA............ $" . number_format($desglose['iva'], 2) . "\n");
                $printer->text("TOTAL.......... $" . number_format($sale->total, 2) . "\n");

                if ($sale->type == 'cash') {
                    $printer->text("EFECTIVO....... $" . number_format($sale->cash, 2) . "\n");
                    if (floatval($sale->change) > 0)  $printer->text("\nCAMBIO......... $" . number_format($sale->change, 2) . "\n");
                } else {
                    $printer->text($sale->type == 'credit' ? "FORMA DE PAGO: CRÉDITO" :  "FORMA DE PAGO:  DEPÓSITO" .  "\n");
                }

                $printer->feed(3);
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("$config->leyend\n");
                $printer->text("$config->website\n");
                $printer->feed(3);
                $printer->cut();
                $printer->close();
            } else {
                Log::info("La tabla configurations está vacía, no es posible imprimir la venta");
            }
            //
        } catch (\Exception $th) {
            Log::info("Error al intentar imprimir el comprobante de venta \n {$th->getMessage()}");
        }
    }

    // recibo de pago / abono
    public  function printPayment($payId)
    {
        try {
            $config = Configuration::first();

            if ($config) {
                $connector = new WindowsPrintConnector($config->printer_name);
                $printer = new Printer($connector);
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(2, 2);

                $printer->text(strtoupper($config->business_name) . "\n");

                $printer->setTextSize(1, 1);
                $printer->text("$config->address \n");
                $printer->text("NIT: $config->taxpayer_id \n");
                $printer->text("TEL: $config->phone \n\n");

                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("==  Comprobante de Pago ==" . "\n\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);

                $payment = Payment::with('sale')->where('id', $payId)->first();

                $printer->text("Folio:" . $payment->id . "\n");
                $printer->text("Fecha:" . Carbon::parse($payment->created_at)->format('d-m-Y H:i') . "\n");
                $printer->text("Cliente:" . $payment->sale->customer->name . "\n");
                $printer->text("=============================================" . "\n");
                $printer->text("Compra: $" . $payment->sale->total . "\n");
                $printer->text("Abono: $" . $payment->amount . "\n");

                if ($payment->sale->debt <= 0) {
                    $printer->text("CRÉDITO LIQUIDADO \n");
                } else {
                    $printer->text("Deuda actual: $" . $payment->sale->debt . "\n\n");
                }

                $printer->text("Forma de Pago Final: " . match ($payment->pay_way) {
                    'cash' => 'EFECTIVO',
                    'credit' => 'CREDITO',
                    'card' => 'TARJETA', // Añadido
                    'simpe' => 'SIMPE MOVIL', // Opción para Simpe Móvil
                    default => 'DESCONOCIDO'
                } . "\n");

                // if ($payment->pay_way == 'deposit') {
                //     $printer->text($payment->bank . "\n");
                //     $printer->text("No. Cuenta:" . $payment->account_number . "\n");
                //     $printer->text("No. Depósito:" . $payment->deposit_number . "\n");
                // }



                $printer->text("=============================================" . "\n");
                $printer->text("Atiende:" . $payment->sale->user->name . "\n");


                $printer->feed(3);
                $printer->cut();
                $printer->close();
            } else {
                Log::info("La tabla configurations está vacía, no es posible imprimir el comprobante de pago");
            }
            //
        } catch (\Exception $th) {
            Log::info("Error al intentar imprimir el comprobante de pago \n {$th->getMessage()}");
        }
    }



    // Definir una función para cortar una cadena si es más larga que un límite y devolver un arreglo
    function cortar($cadena, $limite)
    {
        // Crear un arreglo vacío
        $resultado = array();
        // Si la cadena es más corta o igual que el límite, se agrega al arreglo sin modificar
        if (strlen($cadena) <= $limite) {
            $resultado[] = $cadena;
        }
        // Si la cadena es más larga que el límite, se busca el último espacio dentro del límite
        else {
            $ultimo_espacio = strrpos(substr($cadena, 0, $limite), ' ');
            // Se agrega al arreglo la primera parte de la cadena hasta el último espacio
            $resultado[] = substr($cadena, 0, $ultimo_espacio);
            // Se agrega al arreglo la segunda parte de la cadena desde el último espacio más uno
            $resultado[] = substr($cadena, $ultimo_espacio + 1);
        }
        // Se devuelve el arreglo
        return $resultado;
    }




    function printCashCount($user_name, $dfrom, $dto, $totales, $payments, $credit)
    {
        try {

            $config = Configuration::first();

            if ($config) {
                $connector = new WindowsPrintConnector($config->printer_name);
                $printer = new Printer($connector);

                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(2, 2);

                $printer->text(strtoupper($config->business_name) . "\n");
                $printer->setTextSize(1, 1);
                $printer->text("Corte de Caja $config->taxpayer_id \n\n");


                $printer->setJustification(Printer::JUSTIFY_LEFT);

                $printer->text("=============================================\n");
                $printer->text("Fechas: desde" . $dfrom . ' hasta ' . $dto . "\n");
                $printer->text("Usuario: " . $user_name . " \n");
                $printer->text("=============================================\n");

                $printer->text("VENTAS TOTALES: " . $totales  . "\n");
                $printer->text("VENTAS A CRÉDITO: " . $credit  . "\n");
                $printer->text("PAGOS REGISTRADOS: " . $payments  . "\n");

                $printer->text("---------" . "\n");


                $printer->feed(3);
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->cut();
                $printer->close();
            } else {
                Log::info("La tabla configurations está vacía, no es posible imprimir el corte de caja");
            }
            //
        } catch (\Exception $th) {
            Log::info("Error al intentar imprimir el corte de caja \n {$th->getMessage()} ");
        }
    }
}
