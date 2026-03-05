<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientAccessToken extends Model
{
    protected $fillable = [
        'client_id',
        'token',
        'expires_at',
        'project_id',
        'last_used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    public function isValid(): bool
    {
        return $this->expires_at->isFuture();
    }


    public function client()
    {
        return $this->belongsTo(User::class, "client_id");
    }
    public function project()
    {
        return $this->belongsTo(Project::class, "project_id");
    }
}
