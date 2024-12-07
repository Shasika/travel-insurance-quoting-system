<?php

use App\Livewire\TravelQuote;
use Illuminate\Support\Facades\Route;

Route::get('/', TravelQuote::class)->name('home');
