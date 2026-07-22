<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Bangun sistem yang sederhana, aman, dan dapat dipelihara.');
})->purpose('Display an inspiring quote');
