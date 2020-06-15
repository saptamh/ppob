<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PettyCash extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'budget_for',
        'number',
        'date',
        'noted_news',
        'name_bank_from',
        'name_bank_to',
        'nominal',
        'project_id',
        'upload',
    ];
}
