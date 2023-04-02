<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table='tasks';
    protected $fillable = [
        'user_id',
        'project_id',
        'name',
        'description',
        'status',
        'start_date',
        'end_date'

    ];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function TaskAssign(){
        return $this->hasMany(TaskAssign::class,'task_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comment(){
        return $this->hasMany(Comment::class,'task_id','id');
    }

    public function taskattachment(){
        return $this->hasMany(TaskAttachment::class,'task_id','id');
    }



}
