<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $primaryKey = 'project_id';

    // تم إزالة المسافة الزائدة بعد company_name
    protected $fillable = [
        'project_name', 
        'company_name', 
        'project_description', 
        'start_project', 
        'end_project', 
        'status', 
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id', 'project_id');
    }
}