<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model
{

    protected $fillable = [
        "name",
        "description",
    ];


    public function projects()
    {
        return $this->hasMany(Project::class, "type_id");
    }

    public function taskTemplates()
    {
        return $this->hasMany(TaskTemplate::class, "project_type_id");
    }
}
