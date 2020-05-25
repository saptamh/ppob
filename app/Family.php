<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'employee_id',
        'name',
        'birth_date',
        'relation_type',
        'address',
        'phone',
        'sex'
    ];
}
