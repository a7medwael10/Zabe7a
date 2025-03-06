<?php

use Illuminate\Support\Facades\Route;

Livewire::setScriptRoute(function($handle) {
    return Route::get('/zabe7a/public/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function($handle) {
    return Route::get('/zabe7a/public/livewire/update', $handle);
});
