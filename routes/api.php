<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\TransferController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*========== registration route =============================================*/
Route::post('register/', [RegisterController::class, 'register']);
/*========== login route ====================================================*/
Route::post('login/', [LoginController::class, 'login']);


/*========== authentication is required before a user can access the resource ===================*/
//Route::middleware('auth:api')->group(function(){
    /*========== deposit routes =============================================*/
    Route::post('deposit/', [DepositController::class, 'deposit']);
    /*========== transfer routes ============================================*/
    Route::post('transfer', [TransferController::class, 'transfer']);
    /*========== balance routes =============================================*/
    Route::get('balance', [BalanceController::class, 'balance']);
//});







