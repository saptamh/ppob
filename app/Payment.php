<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'payment_method',
        'paid_date',
        'payment_status',
        'upload',
        'description',
        'source_id',
    ];

    public function Project()
    {
        return $this->belongsTo('App\Project');
    }

    public function Source()
    {
        return $this->belongsTo('App\Project', 'source_id');
    }
}
