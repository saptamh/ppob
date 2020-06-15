<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nonpurchase extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'non_purchases';

    public $timestamps = true;

    protected $fillable = [
        'number',
        'type_object',
        'date',
        'type',
        'type_other',
        'payment',
        'nominal',
        'description',
        'upload',
        'project_id',
    ];
}
