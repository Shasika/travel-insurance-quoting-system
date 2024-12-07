<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceQuote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'destination',       // Add your attributes here
        'start_date',
        'end_date',
        'coverage_options',
        'number_of_travelers',
        'price',
    ];
}
