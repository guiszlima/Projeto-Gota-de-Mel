<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'CPF',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_pending' => 'boolean', // Define is_pending como um atributo booleano
    ];

    /**
     * Boot method to set default values.
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            // Definir is_pending como false por padrão durante a criação
            $user->is_pending = true;
        });
    }

    public function isAdmin()
    {
        return $this->role_id === 3; // ID 3 representa o role de Administrador na tabela roles
    }

    /**
     * Check if the user is a manager.
     *
     * @return bool
     */
    public function isManager()
    {
        return $this->role_id === 2; // ID 2 representa o role de Gerente na tabela roles
    }

    /**
     * Check if the user is a salesperson.
     *
     * @return bool
     */
    public function isSalesperson()
    {
        return $this->role_id === 1; // ID 1 representa o role de Vendedor na tabela roles
    }


}
