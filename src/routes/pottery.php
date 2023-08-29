<?php


// use Illuminate\Support\Facades\Route;


  Route::get('/example/pottery', [\Pottery\Http\Controllers\PagePotteryController::class, 'index']);
