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
        'start_date'
    ];

    public function ProjectValue()
    {
        return $this->hasOne('App\ProjectValue');
    }

    public function ProjectProgress()
    {
        return $this->hasOne('App\ProjectProgress');
    }

    public function ProjectHistorical()
    {
        return $this->hasOne('App\ProjectHistorical');
    }
}
