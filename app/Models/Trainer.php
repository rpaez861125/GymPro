<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trainer extends Model
{
    use HasFactory;
    protected $table = 'trainers';

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'specialty',
        'photo_url',
        'status',
        'salary'
    ];

    public function clients()
    {
        return $this->hasMany(Client::class, 'trainer_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
