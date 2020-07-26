<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kpi extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'start_date',
        'end_date',
        'employee_id',
        'job_percentage',
        'quality_percentage',
        'attitude_percentage',
        'result'];

    public function Employee()
    {
        return $this->hasOne('App\Employee', 'id', 'employee_id');
    }
}
