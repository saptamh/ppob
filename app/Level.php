<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'level',
    ];
}
