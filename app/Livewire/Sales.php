<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Sale;
use App\Models\Product;
use Livewire\Component;
use App\Models\Customer;
use App\Traits\UtilTrait;
use App\Models\SaleDetail;
use App\Traits\PrintTrait;
use Illuminate\Support\Arr;
use Livewire\Attributes\On;
use App\Models\Configuration;
use App\Traits\JsonTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Sales extends Component
{
    use UtilTrait;
    use PrintTrait;
    use JsonTrait;


    public Collection $cart;
    public $taxCart = 0, $itemsCart, $subtotalCart = 0, $totalCart = 0, $ivaCart = 0;

    public $config, $customer;
    //register customer
    public $cname, $caddress, $cemail, $cphone, $ctype = 'Consumidor Final';

    //pay properties
    public $banks, $cashAmount, $change, $acountNumber, $depositNumber, $bank, $payType = 1, $payTypeName = 'PAGO EN EFECTIVO';

    //buscador avanzado
    public $typeSearch, $search,  $products = [];

    function updatedCashAmount()
    {
        if (floatval($this->totalCart) > 0) {
            $this->change = round(floatval($this->cashAmount) - floatval($this->totalCart));
        }
    }

    public function mount()
    {
        if (session()->has("cart")) {
            $this->cart = session("cart");
        } else {
            $this->cart = new Collection;
        }

        session(['map' => 'Ventas', 'child' => ' Componente ', 'pos' => 'MÓDULO DE VENTAS']);

        $this->config = Configuration::first();

        $this->banks = Bank::orderBy('sort')->get();
        $this->bank = $this->banks[0]->id;
    }


    public function render()
    {
        $this->typeSearch = session('typesearch', 'single');

        $this->cart = $this->cart->sortBy('name');
        $this->itemsCart = $this->totalItems();
        $this->taxCart = round($this->totalIVA());
        $this->subtotalCart = round($this->subtotalCart() / 1.13);
        $this->totalCart = round($this->totalCart());
        $this->ivaCart = round(($this->totalCart() / 1.13) * 0.13);

        $this->customer =  session('sale_customer', null);

        return view('livewire.pos.sales');
    }


    // cart methods
    function ScanningCode($barcode)
    {
        $product = Product::with('priceList')
            ->where('sku', $barcode)
            ->orWhere('name', 'like', "%{$barcode}%")
            ->first();
        if ($product) {
            $this->AddProduct($product);
        } else {
            $this->dispatch('noty', msg: 'NO EXISTE EL CÓDIGO ESCANEADO');
        }
    }


    function AddProduct($product, $qty = 1)
    {

        if ($this->inCart($product->id)) {
            $this->updateQty(null, $qty, $product->id);
            return;
        }

        //iva méxico 16%
        $iva = ($this->config->vat / 100);
        //determinamos el precio de venta(con iva)
        if (count($product->priceList) > 0)
            $salePrice = ($product->priceList[0]['price']);
        else
            $salePrice =  $product->price;

        // precio unitario sin iva
        $precioUnitarioSinIva =  $salePrice / (1 + $iva);
        // subtotal neto
        $subtotalNeto =   $precioUnitarioSinIva * intval($qty);
        //monto iva
        $montoIva = $subtotalNeto  * $iva;
        //total con iva
        $totalConIva =  $subtotalNeto + $montoIva;

        $tax = $montoIva;
        $total = $totalConIva;

        $uid = uniqid() . $product->id;

        $coll = collect(
            [
                'id' => $uid,
                'pid' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price1' => $product->price,
                'price2' => $product->price2,
                'sale_price' => $salePrice,
                'pricelist' => $product->priceList,
                'qty' => intval($qty),
                'tax' => $tax,
                'total' => $total,
                'stock' => $product->stock_qty,
                'type' => $product->type,
                'image' => $product->photo,
                'platform_id' => $product->platform_id
            ]
        );

        $itemCart = Arr::add($coll, null, null);
        $this->cart->push($itemCart);
        $this->save();
        $this->dispatch('refresh');
        $this->dispatch('noty', msg: 'PRODUCTO AGREGADO AL CARRITO');
    }



    function Calculator($price, $qty)
    {
        //iva méxico 16%
        $iva = ($this->config->vat / 100); // 0.16;
        //determinamos el precio de venta(con iva)
        $salePrice = $price;
        // precio unitario sin iva
        $precioUnitarioSinIva =  $salePrice / (1 + $iva);
        // subtotal neto
        $subtotalNeto =   $precioUnitarioSinIva * intval($qty);
        //monto iva
        $montoIva = $subtotalNeto  * $iva;
        //total con iva
        $totalConIva =  $subtotalNeto + $montoIva;

        return [
            'sale_price' => $salePrice,
            'neto' => $subtotalNeto,
            'iva' => $montoIva,
            'total' => $totalConIva
        ];
    }



    public function removeItem($id)
    {
        $this->cart = $this->cart->reject(function ($product) use ($id) {
            return $product['pid'] === $id || $product['id'] === $id;
        });

        $this->save();
        $this->dispatch('refresh');
        $this->dispatch('noty', msg: 'PRODUCTO ELIMINADO');
    }


    public function updateQty($uid, $cant = 1, $product_id = null)
    {
        //dd($uid, $cant);
        if (!is_numeric($cant)) {
            $this->dispatch('noty', msg: 'EL VALOR DE LA CANTIDAD ES INCORRECTO');
            return;
        }

        $mycart = $this->cart;

        if ($product_id == null) {
            $oldItem = $mycart->where('id', $uid)->first();
        } else {
            $oldItem = $mycart->where('pid', $product_id)->first();
        }


        $newItem = $oldItem;
        $newItem['qty'] = $product_id == null ? intval($cant) : intval($newItem['qty'] + $cant);

        $values = $this->Calculator($newItem['sale_price'], $newItem['qty']);

        $newItem['tax'] = $values['iva'];

        $newItem['total'] = round($values['total']);


        //delete from cart
        $this->cart = $this->cart->reject(function ($product) use ($uid, $product_id) {
            return $product['id'] === $uid || $product['pid'] === $product_id;
        });

        $this->save();

        //add item to cart
        $this->cart->push(Arr::add(
            $newItem,
            null,
            null
        ));

        $this->save();
        $this->dispatch('refresh');
        $this->dispatch('noty', msg: 'CANTIDAD ACTUALIZADA');
    }

    function setCustomPrice($uid, $price)
    {
        $price = trim(str_replace('$', '', $price));

        if (!is_numeric($price)) {
            $this->dispatch('noty', msg: 'EL VALOR DEL PRECIO ES INCORRECTO');
            return;
        }

        $mycart = $this->cart;

        $oldItem = $mycart->where('id', $uid)->first();


        $newItem = $oldItem;
        $newItem['sale_price'] = $price;

        $values = $this->Calculator($newItem['sale_price'], $newItem['qty']);

        $newItem['tax'] = $values['iva'];

        $newItem['total'] = round($values['total']);


        //delete from cart
        $this->cart = $this->cart->reject(function ($product) use ($uid) {
            return $product['id'] === $uid || $product['pid'] === $uid;
        });

        $this->save();

        //add item to cart
        $this->cart->push(Arr::add(
            $newItem,
            null,
            null
        ));

        $this->save();
        $this->dispatch('refresh');
        $this->dispatch('noty', msg: 'PRECIO ACTUALIZADO');
    }

    public function clear()
    {
        $this->cart = new Collection;
        $this->save();
        $this->dispatch('refresh');
    }

    #[On('cancelSale')]
    function cancelSale()
    {
        $this->resetExcept('config', 'banks');
        $this->clear();
        session()->forget('sale_customer');
    }

    public function totalIVA()
    {
        $iva = $this->cart->sum(function ($product) {
            return $product['tax'];
        });
        return $iva;
    }



    public function totalCart()
    {
        $amount = $this->cart->sum(function ($product) {
            return $product['total'];
        });
        return $amount;
    }



    public function totalItems()
    {
        return   $this->cart->count();
        // $items = $this->cart->sum(function ($product) {
        //     return $product['qty'];
        // });
        // return $items;
    }



    public function subtotalCart()
    {
        $subt = $this->cart->sum(function ($product) {
            return $product['qty'] * $product['sale_price'];
        });
        return $subt;
    }


    public function save()
    {
        session()->put("cart", $this->cart);
        session()->save();
    }


    public function inCart($product_id)
    {
        $mycart = $this->cart;

        $cont = $mycart->where('pid', $product_id)->count();

        return  $cont > 0 ? true : false;
    }

    #[On('sale_customer')]
    function setCustomer($customer)
    {
        session(['sale_customer' => $customer]);
        $this->customer = $customer;
    }



    function initPayment($type)
    {
        $this->payType = $type;

        if ($type == 1) $this->payTypeName = 'PAGO EN EFECTIVO';
        if ($type == 2)   $this->payTypeName = 'PAGO A CRÉDITO';
        if ($type == 3) $this->payTypeName = 'PAGO CON TARJETA';
        if ($type == 4) $this->payTypeName = 'PAGO CON SIMPE MOVIL';

        $this->dispatch('initPay', payType: $type);
    }

    //save sale
    function Store()
    {
        $type = $this->payType;

        //dd(session("cart"));
        //type:  1 = efectivo, 2 = crédito, 3 = depósito
        if (floatval($this->totalCart) <= 0) {
            $this->dispatch('noty', msg: 'AGREGA PRODUCTOS AL CARRITO');
            return;
        }

              // Asignar cliente genérico si no se ha seleccionado uno
       if ($this->customer == null) {
        $this->customer = Customer::firstOrCreate(
            ['name' => 'Consumidor'],
           );
         }


        if ($this->customer == null) {
            $this->dispatch('noty', msg: 'SELECCIONA EL CLIENTE');
            return;
        }

        if ($type == 1) {
            if (!$this->validateCash()) {
                $this->dispatch('noty', msg: 'EL EFECTIVO ES MENOR AL TOTAL DE LA VENTA');
                return;
            }
        }

        if ($type == 3) { // Cambiar a "card" aquí
            // Ya no se necesitan las validaciones para cuenta y depósito
            if (empty($this->customer['name'])) {
                $this->dispatch('noty', msg: 'SELECCIONA EL CLIENTE');
                return;
            }
        }

        // Validación para Simpe Móvil
        if ($type == 4) {
            if (empty($this->customer['name'])) {
                $this->dispatch('noty', msg: 'INGRESA EL NOMBRE DEL CLIENTE QUE HACE EL SIMPE MÓVIL');
                return;
            }
        }


        DB::beginTransaction();
        try {

            //store sale
            $notes = null;

            if ($type == 3) { // Para "card"
                $notes = "Pago por tarjeta";
            }

            if ($type > 1) $this->cashAmount = 0;

            $sale = Sale::create([
                'total' => $this->totalCart,
                'discount' => 0,
                'items' => $this->itemsCart,
                'customer_id' => $this->customer['id'],
                'user_id' => Auth()->user()->id,
                'type' => match ($type) {
                    1 => 'cash',
                    2 => 'credit',
                    3 => 'card',
                    4 => 'simpe',
                    default => 'unknown'
                },
                'status' => ($type == 2 ? 'pending' : 'paid'),
                'cash' => $this->cashAmount,
                'change' => $type == 1 ? round(floatval($this->cashAmount) - floatval($this->totalCart)) : 0,
                'notes' => $notes
            ]);


            // get cart session
            $cart = session("cart");

            // insert sale detail
            $details = $cart->map(function ($item) use ($sale) {
                return [
                    'product_id' => $item['pid'],
                    'sale_id' => $sale->id,
                    'quantity' => $item['qty'],
                    'regular_price' => $item['price2'] ?? 0,
                    'sale_price' => $item['sale_price'],
                    'created_at' => Carbon::now(),
                    'discount' => 0
                ];
            })->toArray();

            SaleDetail::insert($details);

            //update stocks
            foreach ($cart as  $item) {
                Product::find($item['pid'])->decrement('stock_qty', $item['qty']);
            }




            DB::commit();

            $this->dispatch('noty', msg: 'VENTA REGISTRADA CON ÉXITO');
            $this->dispatch('close-modalPay', element: $type == 3 ? 'modalDeposit' : 'modalCash');
            $this->resetExcept('config', 'banks', 'bank');
            $this->clear();
            session()->forget('sale_customer');

            // base64 / printerNINJA
            $b64 = $this->jsonData2($sale->id);

            //evento
            $this->dispatch('print-json', data: $b64);


            //
        } catch (\Exception $th) {
            DB::rollBack();
            $this->dispatch('noty', msg: "Error al intentar guardar la venta \n {$th->getMessage()}");
        }
    }

    function validateCash()
    {
        $total = floatval($this->totalCart);
        $cash = floatval($this->cashAmount);
        if ($cash < $total) {
            return false;
        }

        return true;
    }

    function storeCustomer()
    {

        if (!$this->validaProp($this->cname)) {
            $this->addError('cname', 'INGRESA EL NOMBRE');
            return;
        }

        $customer =  Customer::create([
            'name' => $this->cname,
            'address' => $this->caddress,
            'email' => $this->cemail,
            'phone' => $this->cphone,
            'type' => $this->ctype
        ]);

        session(['sale_customer' => $customer->toArray()]);
        $this->customer = $customer->toArray();

        $this->reset('cname', 'cphone', 'cemail', 'caddress', 'ctype');
        $this->dispatch('close-modal-customer-create');
    }

    function printLast()
    {
        $sale = Sale::latest()->first();
        if ($sale != null && $sale->count() > 0) {
            $this->printSale($sale->id);
        } else {
            $this->dispatch('noty', msg: 'NO HAY VENTAS REGISTRADAS');
        }
    }

    //buscador avanzado
    public function updatedSearch()
    {
        if (strlen($this->search) >= 0) { // dispara la consulta si la propiedad $search tiene 2 o mas caracteres
            $this->products = Product::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('sku', 'like', '%' . $this->search . '%')
                ->take(5)
                ->get();
        } else {
            $this->products = [];
        }
    }

    public function hideResults()
    {
        $this->products = [];
    }

    function AddProduct_FromAdvancedSearch($productId)
    {
        $product = Product::with('priceList')
            ->find($productId);


        if ($product) {
            $this->search = null;
            $this->products = [];
            $this->AddProduct($product);
        } else {
            $this->dispatch('noty', msg: 'NO EXISTE EL PRODUCTO SELECCIONADO');
        }
    }

    #[On('toggleBuscador')]
    function toggleBuscador()
    {
        session(['typesearch' => ($this->typeSearch == 'single' ? 'advance' : 'single')]);
        $this->dispatch('focussearch', input: $this->typeSearch == 'single' ? 'inputSearchAdvance' : 'inputSearch');
    }
}
