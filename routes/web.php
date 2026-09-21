<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('home');
});
Route::view('/catalogue', 'catalogue')->name('catalogue');