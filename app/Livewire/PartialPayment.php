<?php

namespace App\Livewire;

use App\Models\Bank;
use App\Models\Payment;
use App\Models\Sale;
use App\Traits\PrintTrait;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PartialPayment extends Component
{
    use PrintTrait;

    public $sales, $banks, $pays;
    public  $search, $sale_selected_id, $customer_name, $debt;
    public $amount, $acountNumber, $depositNumber, $bank;

    function mount($key = null)
    {
        $this->banks = Bank::orderBy('sort')->get();
        $this->bank = 0;

        $this->sales = [];
        $this->pays = [];
        $this->amount = null;
        $this->search = null;
        $this->sale_selected_id = null;
        $this->customer_name = null;
    }


    public function render()
    {

        $this->getSalesWithDetails();

        return view('livewire.payments.partial-payment');
    }

    public  function getSalesWithDetails()
    {
        if (empty(trim($this->search)) || strlen(trim($this->search)) == 0) {
            $this->sales = Sale::whereHas('customer')
                ->where('type', 'credit')
                ->where('status', 'pending')
                ->with(['customer', 'payments'])
                ->withSum('payments', 'amount')
                ->take(5)
                ->orderBy('sales.id', 'desc')
                ->get();
        } else {

            /*
        El problema que estás experimentando es debido a cómo funciona el método with en Eloquent. Cuando usas with para cargar una relación, Laravel ejecuta dos consultas SQL. Primero, obtiene todas las ventas que coinciden con tus condiciones (type es ‘credit’ y status es ‘pending’). Luego, toma todos los IDs de los clientes de esas ventas y ejecuta una segunda consulta para obtener los clientes.

El problema es que estás tratando de limitar los clientes en la segunda consulta con una condición (name like search). Pero si un cliente no coincide con esa condición, simplemente no se incluirá en los resultados, por lo que tendrás ventas con customer establecido en null.

Para solucionar este problema, puedes usar el método whereHas para filtrar las ventas que tienen un cliente que coincide con tu condición. Aquí te dejo un ejemplo de cómo podrías hacerlo:
        */

            // $sales = Sale::with(['customer' => function ($query) {
            //     $query->where('name', 'like', "%{$this->search}%");
            // }])
            //     ->where('type', 'credit')
            //     ->where('status', 'pending')
            //     ->withSum('payments', 'amount')
            //     ->get();

            //opcion 2

            $sales = Sale::whereHas('customer', function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
                ->where('type', 'credit')
                ->where('status', 'pending')
                ->with(['customer', 'payments'])
                ->withSum('payments', 'amount')
                ->take(15)
                ->orderBy('sales.id', 'desc')
                ->get();

            /*
            En este código, whereHas filtra las ventas para incluir solo aquellas que tienen un cliente que coincide con tu condición. Luego, with carga la relación customer y payments para esas ventas. Esto debería darte el resultado que esperas
 */

            //dd($sales);
            $this->search = null;
            $this->dispatch('clear-search');
            $this->sales = $sales;
        }
    }



    function initPay($sale_id, $customer, $debt)
    {
        $this->sale_selected_id = $sale_id;
        $this->customer_name = $customer;
        $this->debt = $debt;
        $this->dispatch('focus-partialPayInput');
    }

    function doPayment()
    {
        $this->resetValidation();

        if ($this->bank != 0) {
            if (empty($this->acountNumber)) {
                $this->addError('nacount', 'INGRESA EL NÚMERO DE CUENTA');
            }
            if (empty($this->depositNumber)) {
                $this->addError('ndeposit',  'INGRESA EL NÚMERO DE DEPÓSITO');
            }
        }
        if (empty($this->amount) || strlen($this->amount) < 1) {
            $this->addError('amount', 'INGRESA EL MONTO');
        }
        if (floatval($this->amount) <= 0) {
            $this->addError('amount', 'MONTO DEBE SER MAYOR A CERO');
        }

        if (count($this->getErrorBag()) > 0) {
            return;
        }

        $type = null;
        $amount = floatval($this->amount);
        if (floatval($this->amount) >= floatval($this->debt)) {
            $type = 'settled'; //liquida crédito
        } else {
            $type = 'pay'; // abono
        }

        if (floatval($this->amount) > floatval($this->debt)) {
            $amount = $this->debt;
        }


        DB::beginTransaction();

        try {
            //crear pago
            $pay =  Payment::create(
                [
                    'user_id' => Auth()->user()->id,
                    'sale_id' => $this->sale_selected_id,
                    'amount' => floatval($amount),
                    'pay_way' => ($this->bank == 0 ? 'cash' : 'deposit'),
                    'type' => $type,
                    'bank' => ($this->bank == 0 ? '' : $this->banks->where('id', $this->bank)->first()->name),
                    'account_number' => $this->acountNumber,
                    'deposit_number' => $this->depositNumber
                ]
            );

            // actualizar status venta
            if ($type == 'settled') {
                Sale::where('id', $this->sale_selected_id)->update([
                    'status' => 'paid'
                ]);
            }

            DB::commit();

            $this->printPayment($pay->id);
            $this->dispatch('noty', msg: 'PAGO REGISTRADO CON ÉXITO');
            $this->resetExcept('banks', 'pays');

            //
        } catch (\Exception $th) {
            DB::rollBack();

            $this->dispatch('noty', msg: "Error al intentar eliminar el producto \n {$th->getMessage()}");
        }
    }

    function cancelPay()
    {
        $this->sale_selected_id = null;
        $this->customer_name = null;
        $this->debt = null;
    }

    function historyPayments(Sale $sale)
    {
        $this->pays = $sale->payments;
        $this->dispatch('show-payhistory');
    }

    function printReceipt($sale_id)
    {
        $this->printPayment($sale_id);
    }
}
