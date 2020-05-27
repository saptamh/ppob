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
        'payment_date',
        'receipe',
        'description',
    ];

    public function Employee()
    {
        return $this->belongsTo('App\Employee');
    }
}
