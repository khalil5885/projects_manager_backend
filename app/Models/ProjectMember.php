<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    protected $fillable = [
        "project_id",
        "user_id",
        "project_role",

    ];
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
    public function project()
    {
        return $this->belongsTo(Project::class, "project_id");
    }
}
