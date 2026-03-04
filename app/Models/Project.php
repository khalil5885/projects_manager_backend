<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function user()
    {
        return $this->belongsToMany(User::class, 'project_members');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

}
