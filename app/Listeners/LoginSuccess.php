<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Configuration;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginSuccess
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        // Verificar si el usuario está suspendido
        if ($event->user->status != 'Active') {

            Auth::logout();

            throw ValidationException::withMessages([
                'error_message' => 'Tu cuenta está suspendida. Por favor contacta al administrador',
            ]);

            return redirect('/login');
        }

        // si no existe, creamos config defecto
        if (Configuration::count() == 0) {
            Configuration::create([
                'business_name' => 'POSCR',
                'address' => 'San josé Costa Rica',
                'phone' => '72495763',
                'taxpayer_id' => 'RUT58',
                'vat' => 16,
                'printer_name' => '80mm',
                'leyend' => 'Gracias por su compra!',
                'website' => 'idicr.com',
                'credit_days' => 15
            ]);
        }

        session(['settings' => Configuration::first()]);


        $sales = Sale::where('type', 'credit')->where('status', 'pending')->orderBy('id', 'asc')
            ->where('created_at', '<', Carbon::now()->subDays(session('settings')->credit_days))
            ->with('customer')
            ->get();

        if ($sales != null && $sales->count() > 0) {
            session(['noty_sales' => $sales]);
        }
    }
}
