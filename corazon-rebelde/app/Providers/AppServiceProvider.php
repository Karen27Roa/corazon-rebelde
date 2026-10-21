<?php

namespace App\Providers;

use App\Models\Especialista;
use App\Models\Servicio;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Servicio::created(function ($servicio) {
            foreach (Especialista::all() as $especialista) {
                $especialista->servicios()->syncWithoutDetaching([$servicio->id]);
            }
        });
    }
}