<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telefono',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relaciones
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function especialista()
    {
        return $this->hasOne(Especialista::class);
    }

    // Helpers de rol
    public function esAdmin()
    {
        return $this->role === 'admin';
    }

    public function esEspecialista()
    {
        return $this->role === 'especialista';
    }

    public function esCliente()
    {
        return $this->role === 'cliente';
    }
}