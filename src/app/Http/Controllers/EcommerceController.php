<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class EcommerceController extends Controller
{

    public function login(Request $request)
    {
        $input = $request->all();
        try {
            return response()->json(['status' => 'success', 'msg' => 'exito'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'msg' =>  'Internal Server Error'], 500);
        }
    }

    public function productList(Request $request)
    {
        $input = $request->all();
        try {
            $products = Product::where('active', true)->get();
            return response()->json(['status' => 'success', 'msg' => 'exito', 'data' => ['product_list' => $products]], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'msg' =>  'Internal Server Error'], 500);
        }
    }
}
