<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;
    protected $table = 'appointments';
    protected $fillable = [
        'doctorID',
        'supportID',
        'name',
        'email',
        'phone',
        'gender',
        'age',
        'problem',
        'date',
        'time',
    ];

    /**
     * Get the user (doctor) associated with the appointment.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctorID');
    }
}
