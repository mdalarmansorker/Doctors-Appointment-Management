<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;
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
        'status'
    ];

    /**
     * Get the user (doctor) associated with the appointment.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctorID');
    }

    public function support()
    {
        return $this->belongsTo(User::class, 'supportID');
    }
}
