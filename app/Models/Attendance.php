<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $table = 'attendances';

    protected $fillable = [
        'client_id',
        'client_name',
        'check_in_date',
        'check_in_time',
        'notes',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
