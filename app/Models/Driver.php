<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'vehicle',
        'schedule',
        'salary',
        'status',
        'notes',
    ];
    public function clients()
    {
        return $this->hasMany(Client::class, 'driver_id');
    }
}
