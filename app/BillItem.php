<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillItem extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'project_id',
        'bill_code',
        'bill_type',
        'bill_date',
        'pic_name',
        'work_area',
        'description',
    ];

    public function Project() {
        return $this->hasOne('App\Project');
    }
}
