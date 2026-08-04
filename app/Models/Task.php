<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Employee;

class Task extends Model
{
    use HasFactory;

    // تحديد المفتاح الأساسي للجدول
    protected $primaryKey = 'task_id'; 

    protected $fillable = [
    'task_title',
    'company_name',
    'project_id',    
    'assigned_to',
    'task_description',
    'start_task',
    'end_task',
    'status',
];


    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'task_id', 'task_id');
    }

    public function assignedUser()
{
    return $this->belongsTo(Employee::class, 'assigned_to', 'employee_id');
}
}