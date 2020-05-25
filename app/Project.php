<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'no_contract',
        'name',
        'address',
        'customer',
        'pic_customer',
        'work_type',
        'start_date',
        'end_date',
    ];
}
