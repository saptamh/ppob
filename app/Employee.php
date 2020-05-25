<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'nik',
        'name',
        'address',
        'religion',
        'education',
        'location',
        'is_merried',
        'sex',
        'start_date',
        'end_date',
        'npwp',
        'no_npwp',
        'ktp', ];
}
