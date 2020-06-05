<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'project_id',
        'supplier_name',
        'supplier_address',
        'supplier_phone',
        'term_of_payment',
        'down_payment',
        'due_date',
        'incoming_date',
        'payment_status',
        'shipping_address',
    ];

    public function Project()
    {
        return $this->hasOne('App\Project', 'id', 'project_id');
    }
}
