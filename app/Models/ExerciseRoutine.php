<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseRoutine extends Model
{

    use HasFactory;

    protected $table = 'exercises_routines';

    protected $fillable = [
        'name',
        'description',
        'difficulty',
        'duration_minutes',
        'category',
        'exercises',
    ];
    public function clients()
    {
        return $this->hasMany(Client::class, 'routine_id');
    }
}
