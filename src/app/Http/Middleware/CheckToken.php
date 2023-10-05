<?php

namespace App\Http\Middleware;

use App\Http\Services\TokenServices;
use App\Models\CommissionAgent;
use App\Models\CommissionAgentCredential;
use App\Models\CommissionAgentModule;
use App\Models\CommissionAgentSession;
use App\Models\PrivateToken;
use App\Models\User;
use App\Models\UserSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Closure;

class CheckToken
{

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        $session_id = $request->header('session-id');
        //VALIDAR QUE RECIBA DATOS DEL TOKEN
        if (!$token || !$session_id) {
            return response()->json(['status' => 'error', 'msg' => 'Unauthorized'], 401);
        }
        //VALIDAR QUE EXISTA EL TOKEN Y ESTE ACTIVO
        $tokenModel = User::where(['token' => $token, 'active' => true])->first();
        if ($tokenModel) {
            $sessionModel = UserSession::where(['id' => $session_id, 'user_id' => $tokenModel->id, 'active' => true])->first();
            if ($sessionModel) {
                //VALIDAR QUE LA SESSION ESTE ACTIVA
                $now = Carbon::now();
                $expired_at = Carbon::parse($sessionModel->expired_at);
                if ($now->isBefore($expired_at)) {
                    //SESSION EXITOSA
                    $request->merge(['user_id' => $tokenModel->id, 'session_id' => $tokenModel->id]);
                    return $next($request);
                } else {
                    //CERRAR LA SESION
                    $sessionModel->active = false;
                    $sessionModel->save();
                    //TOKEN EXPIRADO
                    return response()->json(['status' => 'error', 'msg' => 'Expired token'], 401);
                }
            }
        }
        //TOKEN INVALIDO
        return response()->json(['status' => 'error', 'msg' => 'Invalid token'], 401);
    }
}
