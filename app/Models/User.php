<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles,HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

public function teacher()
{
    return $this->hasOne(Teacher::class);
}

public function student()
{
    return $this->hasOne(Student::class);
}

public function parent()
{
    return $this->hasOne(Parents::class,'user_id', 'id');
}

public function complaintsFrom()
{
    return $this->hasMany(Complaint::class, 'complaint_from_id');
}

public function complaintsTo()
{
    return $this->hasMany(Complaint::class, 'complaint_to_id');
}


}
