<?php
use App\Http\Controllers\OperationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Get the list of all users
/* Route::get('/users', function(){
    return 'users';
}); */
//Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Auth protected routes
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/create-operation', [OperationController::class, 'store']);
    Route::post('/create-customer', [CustomerController::class, 'store']);
    Route::get('/find-customer/{id}', [CustomerController::class, 'show']);
    Route::get('/find-operation/{id}', [OperationController::class, 'show']);
    Route::put('/approve-operation/{id}', [OperationController::class, 'approve']);
    Route::delete('/reject-operation/{id}', [OperationController::class, 'reject']);
    Route::get('/pending-operations', [OperationController::class, 'pending']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
