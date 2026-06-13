<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodyMeasurement extends Model
{
    use HasFactory;
    protected $table = 'body_measurements';

    protected $fillable = [
        'client_id',
        'client_name',
        'measurement_date',
        'weight',
        'body_fat',
        'chest',
        'waist',
        'hips',
        'arms',
        'legs',
        'notes',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
