<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    // تحديد المفتاح الأساسي للجدول
    protected $primaryKey = 'task_id'; // المفتاح الرئيسي
    protected $guarded = [];
    
    protected $fillable = [
        'task_title',
        'project_name',
        'task_description',
        'start_task',
        'end_task',
        'status',
        'project_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'task_id', 'task_id');
    }
}