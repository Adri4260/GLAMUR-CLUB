<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'description'];

    // Relación: Un rol pertenece a muchos usuarios
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
