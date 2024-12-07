<?php

use App\Livewire\TravelQuote;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});
//Route::get('/posts', function () {
//    return view('posts.index');
//});

Route::get('/', TravelQuote::class)->name('home');
