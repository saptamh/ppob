<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryPayment extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'employee_id',
        'periode',
        'salary',
        'upload',
        'description',
        'work_day',
        'over_time_day',
        'over_time_hour',
        'meal_allowance',
        'bonus',
        'cashbon',
        'total_salary',
        'project_id',
    ];

    public function Employee()
    {
        return $this->belongsTo('App\Employee');
    }

    public function Project()
    {
        return $this->belongsTo('App\Project');
    }
}
