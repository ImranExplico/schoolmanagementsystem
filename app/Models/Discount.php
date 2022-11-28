<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'discount_type', 'discount_rule', 'discount_amount', 'frequency', 'enrolment_year', 'earliest_commencement_start_date', 'earliest_commencement_end_date', 'course_commencement_start_date', 'course_commencement_end_date', 'invoice_commencement_start_date', 'invoice_commencement_end_date', 'remarks', 'status'
    ];

    public function branches()
    {
        return $this->hasMany(DiscountBranch::class);
    }

    public function levels()
    {
        return $this->hasMany(DiscountLevel::class);
    }

    public function courses()
    {
        return $this->hasMany(DiscountCourse::class);
    }
}
