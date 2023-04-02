<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = [
        'client_id',
        'user_id',
        'name',
        'description',
        'status',
        'supervisor',
        'remarks',
        'start_date',
        'end_date'

    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function ProjectAssign()
    {
        return $this->hasMany(ProjectAssign::class, 'project_id', 'id');
    }

    public function task()
    {
        return $this->hasMany(Task::class, 'project_id', 'id');
    }
}
