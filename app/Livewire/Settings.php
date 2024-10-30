<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Configuration;

class Settings extends Component
{
    public $setting_id = 0, $businessName, $phone, $taxpayerId, $vat, $printerName, $website, $leyend, $creditDays = 15, $address;

    function mount()
    {
        session(['map' => 'Configuraciones', 'child' => ' Sistema ', 'pos' => 'Settings']);




        $this->loadConfig();
    }

    public function render()
    {
        return view('livewire.settings');
    }

    function loadConfig()
    {
        $config = Configuration::first();
        if ($config) {
            $this->setting_id = $config->id;
            $this->businessName = $config->business_name;
            $this->address = $config->address;
            $this->phone = $config->phone;
            $this->taxpayerId = $config->taxpayer_id;
            $this->vat = $config->vat;
            $this->printerName = $config->printer_name;
            $this->leyend = $config->leyend;
            $this->website = $config->website;
            $this->creditDays = $config->credit_days;
        }
    }

    function saveConfig()
    {
        $this->resetValidation();


        if (empty($this->businessName)) {
            $this->addError('businessName', 'Ingresa la empresa');
        }
        if (empty($this->address)) {
            $this->addError('address', 'Ingresa la dirección');
        }
        if (empty($this->taxpayerId)) {
            $this->addError('taxpayerId', 'Ingresa el RFC/RUT');
        }
        if (empty($this->vat)) {
            $this->addError('vat', 'Ingresa el IVA');
        }
        if (!is_numeric($this->vat)) {
            $this->addError('vat', 'Ingresa el IVA en números!');
        }
        if (empty($this->printerName)) {
            $this->addError('printerName', 'Ingresa la impresora');
        }
        if (empty($this->creditDays)) {
            $this->addError('creditDays', 'Ingresa días límite de pago');
        }
        if (!is_numeric($this->creditDays)) {
            $this->addError('creditDays', 'Ingresa los días con números');
        }
        if (count($this->getErrorBag()) > 0) {
            return;
        }


        try {
            Configuration::updateOrCreate(
                ['id' => $this->setting_id],
                [
                    'business_name' => trim($this->businessName),
                    'address' => trim($this->address),
                    'phone' => trim($this->phone),
                    'taxpayer_id' => trim($this->taxpayerId),
                    'vat' => trim($this->vat),
                    'printer_name' => trim($this->printerName),
                    'leyend' => trim($this->leyend),
                    'website' => trim($this->website),
                    'credit_days' => intval($this->creditDays)
                ]
            );

            $this->loadConfig();
            $this->dispatch('noty', msg: "Configuración General Actualizada");
            //

        } catch (\Throwable $th) {
            $this->dispatch('noty', msg: "Error al intentar actualizar la configuración general: " . $th->getMessage());
        }
    }
}
