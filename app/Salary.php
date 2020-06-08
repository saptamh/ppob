<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'base_salary',
        'weekend_allowance',
        'working_hour',
        'meal_allowance',
    ];

    public function Employee()
    {
        return $this->hasOne('App\Employee', 'id', 'employee_id');
    }
}
