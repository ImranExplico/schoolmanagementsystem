<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     * 
     */
    protected $fillable = [
        'name', // #
        'class_id', //Level #
        'school_id', //Branch #
        'session_id', // #
        'code', // #
        'status', // #
        'department_id', //Course  #
        'class_number', 
        'class_room_id', 
        'stream', 
        'f2f_enrollment_size', 
        'online_enrollment_size', 
        'enrollment_year', 
        'remarks', 
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'day',
        'created_at', // #
        'updated_at', // #
        'enrollment_type'
    ];
}
