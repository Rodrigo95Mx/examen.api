<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EcommerceController extends Controller
{

    public function register(Request $request)
    {
        $input = $request->all();
        try {
            $validator = Validator::make($input, [
                'register_name' => 'required',
                'register_lastname' => 'required',
                'register_lastname2' => 'required',
                'register_email' => 'required',
                'register_phone' => 'required',
                'register_password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'msg' => 'Cuerpo de entrada invalido'], 400);
            }

            //VERIFICAR SI EXISTE EL EMAIL
            $checkUser = User::where('email', $input["register_email"])->first();

            if ($checkUser) {
                return response()->json(['status' => 'error', 'msg' => 'El correo electrónico ya se encuentra registrado'], 422);
            } else {
                $new_user = new User();
                $new_user->name = $input['register_name'];
                $new_user->lastname = $input['register_lastname'];
                $new_user->lastname2 = $input['register_lastname2'];
                $new_user->email = $input['register_email'];
                $new_user->phone = $input['register_phone'];
                $new_user->password = Hash::make($input['register_password']);
                $new_user->token = bin2hex(openssl_random_pseudo_bytes(64));
                $new_user->active = true;
                $new_user->save();
                return response()->json(['status' => 'success', 'msg' => 'Usuario registrado'], 200);
            }
            return response()->json(['status' => 'success', 'msg' => 'exito'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'msg' =>  'Internal Server Error'], 500);
        }
    }

    public function login(Request $request)
    {
        $input = $request->all();
        try {

            $validator = Validator::make($input, [
                'commission_agent_id' => 'required|exists:commission_agents,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'msg' => 'Cuerpo de entrada invalido'], 400);
            }


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
