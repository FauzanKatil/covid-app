<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_patients',
        'phone',
        'address',
        'statuses_id',
        'in_date',
        'out_date'
    ];

    function statuses(){
        $this->belongsTo(Statuses::class);
    }
}
