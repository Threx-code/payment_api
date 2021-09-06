<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransferRequest;
use App\Models\Transaction;

class TransferController extends Controller
{

    /**
     * [transfer this method allows users to be able to transfer money from their wallet to another user wallet]
     * @param  TransferRequest $request [This validate the user input to prevent sql injections and make sure all required parameters are properly entered]
     * @param $user Auth::user() this store the authenticated data into the variable $user
     * Check if the $user variable is not null
     * we try to deposit into the authenticated user account
     * @method receiverCurrentBalance($request->receiver) returned the current receiver current balance
     * @method senderCurrentBalance($user['id']) returned the current balance of the sender, stored it into a variable $current_balance to update next trnafer transaction
     * @return [type]  [json response with details of amount transfered, status of the trnasaction, and user current balance]
     */
    public function transfer(TransferRequest $request)
    {
        $user = Auth::user();

        // check if the $user is not null/empty and the request is validated
        if((!is_null($user)) && ($request->validated())){ 
            try{
                // check to make sure the sender current balance is not lower than the sender current balance
                if($this->senderCurrentBalance($user->id) >= $request->amount){

                    // store the receiver current balance aadd the amount being transfered to it
                    $receiver_balance = $this->receiverCurrentBalance($request->receiver) + $request->amount;

                    // create tranfer. If the transfer is successful, perform deduction from senders current balance
                    if(Transaction::create([
                        'send_by' => $user->id,
                        'sent_to' => $request->receiver,
                        'amount' => $request->amount,
                        'type' => 'credit',
                        'current_balance' => $receiver_balance  
                    ])){

                        // update the sender's current balance
                        $senderNewBalance = $this->senderCurrentBalance($user->id) -  $request->amount;
                        $this->updateSenderCurrentBalance($user->id, $senderNewBalance);


                        // let the sender know that the transaction was successful
                        return response()->json([
                            'status' => 200,
                            'success' => true,
                            'message' => 'Transfer Successful',
                            'amountSent' => number_format($request->amount),
                            'newBalance' => number_format($senderNewBalance)
                        ]); 
                    }
                }
                else{
                    // we want to tell the senders that their account balance is lower than the amount they are trying to send
                    return response()->json([
                        'status' => 500,
                        'success' => false,
                        'message' => "Insufficient Fund"
                    ]);
                }

            }
            catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()]);
            }
        }
        else{
            // we want to tell the users that the data they supplied does not match any credentials in the database
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => "Sorry No User Found"
            ]);
        }
    }



    /**
     * [receiverCurrentBalance get the receiver's current balance]
     * @param  [type] $receiver_id [receivers unique Identifier such as unique ID, Account number]
     * @return [type] [return the current balance or 0 if the user does not have any balance yet]
     */
    public function receiverCurrentBalance($receiver_id)
    {
        $currentBalance = Transaction::select('current_balance')
        ->where('sent_to', $receiver_id)
        ->orderby('id', 'desc')
        ->get()
        ->first();

        return $currentBalance['current_balance'] ?? 0;
    }


    /**
     * [getSenderCurrentBalance returned the current balance of the user, stored it into a variable $current_balance to update next deposit transaction]
     * @param  [type] $sender_id [required parameter to fetch the current balance of an authenticated user]
     * @return [type]  [return a current balance or 0 if the user has not performed any transaction]
     */
    public function senderCurrentBalance($sender_id)
    {
        $currentBalance = Transaction::select('current_balance')
        ->where('sent_to', $sender_id)
        ->orderby('id', 'desc')
        ->get()
        ->first();

        return $currentBalance['current_balance'] ?? 0;

    }

    
    /**
     * [updateSenderCurrentBalance update the sender current balance once the transfer is successful]
     * @param  [type] $sender_id [sender's unique identifier such as unique ID or account number]
     * @param  [type] $amount    [the new sender banalance]
     * @return [type] [null]
     */
    public function updateSenderCurrentBalance($sender_id, $amount)
    {
        Transaction::where('sent_to', $sender_id)
        ->orderBy('id', 'desc')
        ->first()
        ->update(['current_balance' => $amount]);
    }


    




}
