<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // تحديد المفتاح الرئيسي المخصص لجدولك
    protected $primaryKey = 'user_id';

    /**
     * الحقول المسموح بتعبئتها جماعياً
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * الحقول المخفية عند تحويل الموديل لـ Array أو JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * تحويل أنواع الحقول
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function client()
{
    return $this->hasOne(Client::class, 'user_id', 'user_id');
}

public function projects()
{
    return $this->hasMany(Project::class);
}

public function tasks()
{
    return $this->hasMany(Task::class);
}


}