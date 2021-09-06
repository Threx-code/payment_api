<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DepositRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class DepositController extends Controller
{
    /**
     * [deposit this method allows authenticated users to be able to deposit money into their wallet/account]
     * @param  DepositRequest $request [This sanitizes all user input to make prevent sql injects and also check if all required parameters are properly entered]
     * @param $user Auth::user() this store the authenticated data into the variable $user
     * Check if the $user variable is not null
     * we try to deposit into the authenticated user account
     * @method getCurrentBalance($user['id']) returned the current balance of the user, stored it into a variable $current_balance to update next deposit transaction
     * @return [type]  [json response with details of amount deposited, status of the trnasaction, and user current balance]
     */
    public function deposit(DepositRequest $request)
    {
        $user = Auth::user();
        if((!is_null($user)) && ($request->validated())){

            try{
                 $current_balance = $this->getCurrentBalance($user['id']) + $request->amount;

                Transaction::create([
                    'send_by' => $user->id,
                    'sent_to' => $user->id,
                    'amount' => $request->amount,
                    'type' => 'deposit',
                    'current_balance' => $current_balance  
                ]);

                return response()->json([
                    'status' => 201,
                    'success' => true,
                    'amountDeposited' => number_format($request->amount),
                    'currentBalance' => number_format($current_balance)

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


    /**
     * [getCurrentBalance returned the current balance of the user, stored it into a variable $current_balance to update next deposit transaction]
     * @param  [type] $sent_to [required parameter to fetch the current balance of an authenticated user]
     * @return [type]  [return a current balance or 0 if the user has not performed any transaction]
     */
    public function getCurrentBalance($sent_to)
    {
        $currentBalance = Transaction::select('current_balance')
        ->where('sent_to', $sent_to)
        ->orderby('id', 'desc')
        ->get()
        ->first();

        return $currentBalance['current_balance'] ?? 0;
    }
}
 

