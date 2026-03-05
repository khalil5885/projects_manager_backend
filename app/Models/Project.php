<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
class Project extends Model
{
    protected $fillable = [
        "name",
        "description",
        "status",
        "start_date",
        "end_date",
        "owner_id",
        "created_by",
        "type_id"
    ];
    protected $casts = [
        "start_date" => "date",
        "end_date" => "date"
    ];
    public function projectComments()
    {
        return $this->hasMany(ProjectComment::class);
    }
    public function projectsOwner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'user_id')
            ->withPivot('project_role')
            ->withTimestamps();
    }
    public function type()
    {
        return $this->belongsTo(ProjectType::class, "type_id");
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function accessTokens()
    {
        return $this->hasMany(ClientAccessToken::class, 'project_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
