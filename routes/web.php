<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/mymailtemplate', function () {
    return view('mailTemplate', [
        "details" => [
            "otp" => 1252,
            "title" => "Lupa Password AtmaKitchen"
        ]
    ]);
});
