<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\InfoController;
use App\Http\Controllers\Api\CategotyController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BorrowController;
use App\Http\Controllers\Api\RootController;
use Illuminate\Support\Facades\Route;

// 以下端口游客可访问
Route::get('/book/find/all', [BookController::class, 'getAllBooks']);

Route::get('/book/find/five', [BookController::class, 'getFiveBooks']);

Route::get('/book/find/{id}', [BookController::class, 'getBookInfo']);

Route::get('/book/find/categoty/{categoty_id}', [BookController::class, 'getBooksByType']);

Route::get('/book/find/{type}/{name}', [BookController::class, 'getBook']); 

Route::get('/categoty/find/all', [CategotyController::class, 'getAllCategoty']);

Route::get('/info/find/all', [InfoController::class, 'getAllInfo']);

Route::post('/user/register/send', [UserController::class, 'sendEmailCodeToRegister']);

Route::post('/user/register', [UserController::class, 'register']);

Route::post('/user/login/password', [UserController::class, 'passwordLogin']);

Route::post('/user/find/name', [UserController::class, 'sendEmailCodeToUserName']);

Route::post('/user/find/password/send', [UserController::class, 'sendEmailCodeToPassword']);

Route::post('/user/find/password', [UserController::class, 'resetPassword']);

Route::post('/root/login', [RootController::class, 'login']);



//以下是user权限

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/loginIO', [UserController::class, 'loginIO']);

    Route::post('/user/logout', [UserController::class, 'logout']);

    Route::post('/user/get/info', [UserController::class, 'getUserInfo']);

    Route::post('/user/update/username', [UserController::class, 'updateUserName']);
    
    Route::post('/user/update/img', [UserController::class, 'uploadAvatar']);

    Route::post('/user/update/email/send', [UserController::class, 'resetEmailSendEmail']);

    Route::post('/user/update/email', [UserController::class, 'resetEmail']);

    Route::post('/user/update/password', [UserController::class, 'updateUserPassword']);

    Route::post('/borrow/createBorrow', [BorrowController::class, 'createBorrow']);

    Route::get('/borrow/get/nor', [BorrowController::class, 'getBorrowNorLog']);

    Route::get('/borrow/get/out', [BorrowController::class, 'getBorrowOutLog']);

    Route::get('/borrow/get/lost', [BorrowController::class, 'getBorrowLostLog']);

    Route::get('/borrow/get/back', [BorrowController::class, 'getBackLog']);

    Route::post('/borrow/back', [BorrowController::class, 'backBook']);
});

//以下是root权限
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/root/loginIO', [RootController::class, 'loginIO']);

    Route::get('/root/table/books', [RootController::class, 'getBookTypeNumber']);

    Route::get('/root/table/users', [RootController::class, 'getUserNumber']);

    Route::get('/root/table/borrow', [RootController::class, 'getBorrowNumber']);

    Route::post('/root/logout', [RootController::class, 'logout']);

    Route::get('/root/get/info', [RootController::class, 'getRootInfo']);

    Route::get('/root/get/books', [RootController::class, 'getAllBooks']);

    Route::post('/root/book/delete', [RootController::class, 'delete_book']);
    
    Route::post('/root/book/updata', [RootController::class, 'updata_book']);




    Route::post('/root/info/add', [RootController::class, 'add_info']);

    Route::post('/root/info/delete', [RootController::class, 'delete_info']);


    Route::get('/root/user/all', [RootController::class, 'get_all_user']);

    Route::post('/root/user/ban', [RootController::class, 'user_baned']);

    Route::post('/root/user/ban/off', [RootController::class, 'user_baned_off']);

    Route::post('/root/user/delete', [RootController::class, 'user_delete']);


    Route::post('/root/borrow/all', [RootController::class, 'get_all_borrow']);

    Route::post('/root/borrow/confirm', [RootController::class, 'root_confirm_action']);


    Route::post('/root/reset/password/send', [RootController::class, 'sendEmailCodeToPassword']);

    Route::post('/root/reset/password', [RootController::class, 'resetPassword']);
});