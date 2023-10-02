<?php

namespace App\Http\Controllers;

use App\Http\Services\ExternalServices;
use App\Http\Services\ToolsServices;
use App\Http\Services\TreasuryServices;
use App\Models\CommissionAgent;
use App\Models\CommissionAgentCommission;
use App\Models\CommissionAgentCredential;
use App\Models\CommissionAgentOperation;
use App\Models\CommissionAgentSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
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
}
