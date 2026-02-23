<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calculations extends Model
{
    protected $fillable = [
        'expression',
        'result',
    ];
}
