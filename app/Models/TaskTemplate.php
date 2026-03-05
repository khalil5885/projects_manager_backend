<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskTemplate extends Model
{
    protected $fillable = [
        "project_type_id",
        "title",
        "description",
        "default_due_days",
        "order",

    ];

    public function project_type()
    {
        return $this->belongsTo(ProjectType::class, "project_type_id");
    }

}
