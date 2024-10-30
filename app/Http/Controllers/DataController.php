<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DataController extends Controller
{


    public function autocomplete_customers(Request $request)
    {
        $valueToSearch = $request->get('q');

        $clients = Customer::where('name', 'like', "%{$valueToSearch}%")
            ->orWhere('address', 'like', "%{$valueToSearch}%")
            ->orWhere('email', 'like', "%{$valueToSearch}%")
            ->take(10)->get();

        return response()->json($clients);
    }

    public function autocomplete_suppliers(Request $request)
    {
        $valueToSearch = $request->get('q');

        $suppliers = Supplier::where('name', 'like', "%{$valueToSearch}%")
            ->orWhere('address', 'like', "%{$valueToSearch}%")
            ->orWhere('phone', 'like', "%{$valueToSearch}%")
            ->take(10)->get();

        return response()->json($suppliers);
    }

    public function autocomplete_products(Request $request)
    {
        $valueToSearch = $request->get('q');

        $suppliers = Product::where('name', 'like', "%{$valueToSearch}%")
            ->orWhere('sku', 'like', "%{$valueToSearch}%")
            ->orderBy('name')
            ->take(10)->get();

        return response()->json($suppliers);
    }
}
