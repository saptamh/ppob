<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    public function Role()
    {
        return $this->hasOne('App\Role', 'id', 'role_id');
    }
}
