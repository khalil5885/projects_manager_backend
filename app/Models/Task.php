<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',          // ← was 'name', column is 'title'
        'description',
        'project_id',
        'assigned_to',
        'status',
        'priority',
        'due_date',
    ];



    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // The employee assigned to this task
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Comments on this task
    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }
}