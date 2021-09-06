<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BalanceRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class BalanceController extends Controller
{   
    /**
     * [balance this return current balance of the authenticated users]
     * @return [type] [json response with status, balance]
     */
    public function balance()
    {
        $user = Auth::user();

        if(!is_null($user)){
            try{
                $currentBalance = Transaction::select('current_balance')
                ->where('sent_to', $user['id'])
                ->orderby('id', 'desc')
                ->get()
                ->first();

                return response()->json([
                    'status' => 200,
                    'success' => true,
                    'balance' => number_format($currentBalance['current_balance']) ?? 0.00
                ]);
            }
            catch(Exception $e){
                 return response()->json(['message' => $e->getMessage()]);
            }
        }
        else{
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => "Sorry No User Found"
            ]);
        }
    }
}
