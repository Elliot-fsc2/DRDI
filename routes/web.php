<?php

use App\Livewire\GroupCards;
use Illuminate\Support\Facades\Route;
use App\Filament\Student\Pages\Teststudent;

Route::get('/', function () {
  return redirect()->to(Teststudent::getUrl(panel: 'student'));
});

// Route::get('/test', GroupCards::class);
