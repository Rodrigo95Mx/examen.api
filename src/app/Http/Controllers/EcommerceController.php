<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShoppingCart;
use App\Models\User;
use App\Models\UserSession;
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
                'login_email' => 'required',
                'login_password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'msg' => 'Cuerpo de entrada invalido'], 400);
            }

            //VALIDAR FORMATO EMAIL
            $validator = Validator::make($input, [
                'login_email' => 'email'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'msg' => 'Correo electrónico inválido'], 422);
            }

            $user = User::where('email', $input['login_email'])->first();
            if ($user) {
                if (password_verify($input["login_password"], $user->password)) {
                    //CERRAR LAS SESIONES EXISTENTES
                    UserSession::where(['user_id' => $user->id, 'active' => true])->update(['active' => false]);
                    //CREAR LA SESION
                    $expired_at = new \DateTime(date('Y-m-d H:i:s'));
                    $expired_at = $expired_at->modify('+60 minutes');
                    $expired_at = $expired_at->format("Y-m-d H:i:s");
                    $new_session = new UserSession;
                    $new_session->user_id = $user->id;
                    $new_session->expired_at = $expired_at;
                    $new_session->active = true;
                    $new_session->save();
                    //ACTUALIZAR EL TOKEN DEL USUSARIO
                    $user->token = bin2hex(openssl_random_pseudo_bytes(64));
                    $user->save();
                    //OBTENER EL CARRITO GUARDADO
                    $shoppingCart = ShoppingCart::where(['user_id' => $user->id, 'active' => true])->get();

                    $data_return = [
                        'shopping_cart' => $shoppingCart,
                        'data_session' => [
                            'token' => $user->token,
                            'session_id' => $new_session->id,
                            'expired_at' => $expired_at
                        ]
                    ];
                    return response()->json(['status' => 'success', 'msg' => 'Inicio de session exitoso', 'data' => $data_return], 200);
                }
            }
            return response()->json(['status' => 'error', 'msg' => 'Credenciales incorrectas'], 422);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'msg' =>  'Internal Server Error'], 500);
        }
    }

    public function logout(Request $request)
    {
        $input = $request->all();
        try {
            $session = UserSession::find($input['session_id']);
            $session->active = false;
            $session->save();
            return response()->json(['status' => 'success', 'msg' => 'Session cerrada'], 200);
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

    public function updateShoppingCartDataBase(Request $request)
    {
        $input = $request->all();
        try {
            $validator = Validator::make($input, [
                'shopping_carts' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'msg' => 'Cuerpo de entrada invalido'], 400);
            }

            ShoppingCart::where(['user_id' => $input['user_id'], 'active' => true])->update(['active' => false]);

            foreach ($input['shopping_carts'] as $value) {
                $cart_product = ShoppingCart::where(['user_id' => $input['user_id'], 'product_id' => $value['product_id']])->first();
                if ($cart_product) {
                    $cart_product->quantity = $value['quantity'];
                    $cart_product->active = true;
                    $cart_product->save();
                } else {
                    $new_cart_product = new ShoppingCart;
                    $new_cart_product->user_id = $input['user_id'];
                    $new_cart_product->product_id = $value['product_id'];
                    $new_cart_product->quantity = $value['quantity'];
                    $new_cart_product->active = true;
                    $new_cart_product->save();
                }
            }
            return response()->json(['status' => 'success', 'msg' => 'Carrito actualizado'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'msg' =>  'Internal Server Error'], 500);
        }
    }
}
