<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['task_title', 'task_description', 'begin_task', 'ending_task', 'status', 'project_id'];

   
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

 
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
