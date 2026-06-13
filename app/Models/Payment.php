<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';

    protected $fillable = [
        'client_id',
        'amount',
        'payment_date',
        'payment_method',
        'concept',
        'status',
        'notes',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
