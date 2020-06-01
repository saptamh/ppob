<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectProgress extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'project_id',
        'progress',
        'description',
        'project_value_id',
        'result',
    ];

    public function ProjectValue()
    {
        return $this->belongsTo('App\ProjectValue');
    }
}
