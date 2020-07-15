<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectDaily extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'project_timeline_id',
        'employee_id',
        'job',
        'target',
        'date',
        'realisation',
        'worked_hour',
        'description'];

    public function Employee()
    {
        return $this->hasOne('App\Employee', 'id', 'employee_id');
    }

    public function ProjectTimeline()
    {
        return $this->hasOne('App\ProjectTimeline', 'id', 'project_timeline_id');
    }
}
