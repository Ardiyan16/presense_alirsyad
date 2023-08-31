<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'longitude',
        'latitude',
        'location_details',
        'status',
        'presence_type',
        'date_presence',
        'time',
        'photo',
    ];
}
