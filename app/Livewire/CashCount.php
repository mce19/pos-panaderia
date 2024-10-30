<?php

namespace App\Livewire;

use App\Models\Payment;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use App\Traits\PrintTrait;
use Livewire\Component;
use Livewire\Attributes\On;

class CashCount extends Component
{
    use PrintTrait;

    public $users = [], $user, $user_id = 0, $totales = 0, $dateFrom, $dateTo;
    public $totalSales = 0, $totalCreditSales = 0, $totalPayments = 0;

    function mount()
    {
        session(['map' => "", 'child' => '', 'pos' => 'Arqueo de Caja']);

        $this->users = User::orderBy('name')->get();
    }


    public function render()
    {
        $this->user = session('cashcount_user', 0);

        return view('livewire.cash-count');
    }


    function updatedUserId()
    {
        session(['cashcount_user' => User::find($this->user_id)]);
        $this->user = session('cashcount_user');
    }

    function getSalesBetweenDates()
    {
        if ($this->user_id == null && $this->dateFrom == null && $this->dateTo == null) {
            $this->dispatch('noty', msg: 'SELECCIONA EL USUARIO Y/O LAS FECHAS DE CONSULTA');
            return;
        }

        if (($this->dateFrom != null && $this->dateTo == null) || ($this->dateFrom == null && $this->dateTo != null)) {
            $this->dispatch('noty', msg: 'SELECCIONA LA FECHA DESDE Y HASTA');
            return;
        }


        sleep(1);

        $dFrom = Carbon::parse($this->dateFrom)->startOfDay();
        $dTo = Carbon::parse($this->dateTo)->endOfDay();

        try {
            $sales = Sale::whereBetween('created_at', [$dFrom, $dTo])
                ->when($this->user_id != 0, function ($qry) {
                    $qry->where('user_id', $this->user_id);
                })
                ->select('total', 'type')
                ->get();

            $this->totalSales = $sales->sum('total');
            $this->totalCreditSales = $sales->where('type', 'credit')->sum('total');

            $this->totalPayments = Payment::whereBetween('created_at', [$dFrom, $dTo])
                ->when($this->user_id != 0, function ($qry) {
                    $qry->where('user_id', $this->user_id);
                })
                ->sum('amount');

            $this->dispatch('noty', msg: 'Info actualizada');
            //
        } catch (\Exception $th) {
            $this->dispatch('noty', msg: "Error al obtener la información de las ventas por fecha: {$th->getMessage()} ");
        }
    }

    function getDailySales()
    {
        sleep(1);

        $dFrom = Carbon::today()->startOfDay();
        $dTo = Carbon::today()->endOfDay();
        $this->dateFrom = $dFrom;
        $this->dateTo = $dTo;

        try {
            $sales = Sale::whereBetween('created_at', [$dFrom, $dTo])
                ->when($this->user_id != 0, function ($qry) {
                    $qry->where('user_id', $this->user_id);
                })
                ->select('total', 'type')
                ->get();

            $this->totalSales = $sales->sum('total');
            $this->totalCreditSales = $sales->where('type', 'credit')->sum('total');

            $this->totalPayments = Payment::whereBetween('created_at', [$dFrom, $dTo])
                ->when($this->user_id != 0, function ($qry) {
                    $qry->where('user_id', $this->user_id);
                })
                ->sum('amount');

            $this->dispatch('noty', msg: 'Info actualizada');
            //
        } catch (\Exception $th) {
            $this->dispatch('noty', msg: "Error al obtener la información de las ventas del día:  {$th->getMessage()} ");
        }
    }

    function printCC()
    {
        $username = $this->user_id == 0 ? 'Todos los usuarios' : User::find($this->user_id)->name;
        $this->printCashCount($username, $this->dateFrom, $this->dateTo, $this->totalSales, $this->totalPayments, $this->totalCreditSales);

        $this->dispatch('noty', msg: 'Impresión de corte enviada');
    }
}
