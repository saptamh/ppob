<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{

    public $timestamps = true;

    protected $fillable = [
        'rate',
        'value',
    ];
}
