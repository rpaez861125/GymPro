<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory;
    protected $table = 'clients';

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'birth_date',
        'photo_url',
        'trainer_id',
        'routine_id',
        'driver_id',
        'membership_type',
        'membership_status',
        'membership_expiry_date',
        'join_date',
        'status',
        'emergency_contact',
        'notes',
    ];
    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
    public function exerciseRoutine()
    {
        return $this->belongsTo(ExerciseRoutine::class);
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'client_id');
    }

    public function bodyMeasurements()
    {
        return $this->hasMany(BodyMeasurement::class, 'client_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'client_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
