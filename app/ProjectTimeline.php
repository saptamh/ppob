<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTimeline extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $timestamps = true;

    protected $fillable = [
        'projects_id',
        'type',
        'manager_id',
        'date',
        'project_item_id',
        'project_job_id',
        'project_zone_id',
        'qty',
        'duration'];

    public function Manager()
    {
        return $this->hasOne('App\Employee', 'id', 'manager_id');
    }

    public function Project()
    {
        return $this->hasOne('App\Project', 'id', 'projects_id');
    }

    public function ProjectDaily()
    {
        return $this->hasMany('App\ProjectDaily');
    }

    public function ProjectItem()
    {
        return $this->hasOne('App\ProjectItem', 'id', 'project_item_id');
    }

    public function ProjectJob()
    {
        return $this->hasOne('App\ProjectJob', 'id', 'project_job_id');
    }

    public function ProjectZone()
    {
        return $this->hasOne('App\ProjectZone', 'id', 'project_zone_id');
    }
}
