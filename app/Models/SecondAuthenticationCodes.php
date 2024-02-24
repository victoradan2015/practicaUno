<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondAuthenticationCodes extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'id_usuario',
        'used',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

}
