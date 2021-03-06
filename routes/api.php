<?php

use Illuminate\Http\Request;

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

//For FJ Admin, lets make it easy for him
Route::middleware('throttle:1000,1,1')->post('/fjuser/{fjid}', function (Request $request, $fjid) {
    if($request->input('key') != env('FJ_ADMIN_KEY'))
        abort(403);
    $user = \App\FunnyjunkUser::where('fj_id', $fjid)->orderBy('created_at', 'desc')->firstOrFail();
    logger("API Request for", ["fj_username", $user->username]);
    return $user->user;
});


//For everyone else, fuck you we do it properly here
Route::group(['middleware' => 'throttle:60,1,1'], function(){
    Route::get('/fjuser/basicUserByName/{username}', 'API\FJUserController@getBasicUserByUsername')->middleware(['auth:api', 'scope:fjapi-userinfo-basic']);
});