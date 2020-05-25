<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectHistorical extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'project_id',
        'point',
        'duration',
        'weight',
        'bast',
        'date',
        'retention',
    ];
}
