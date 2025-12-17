<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'grade_level',
        'subject_focus',
        'bio',
        'points',
        'level',
        'streak_days',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    public function subjectsTaught()
    {
        return $this->hasMany(Subject::class, 'teacher_id');
    }

    public function subjectsEnrolled()
    {
        return $this->belongsToMany(Subject::class, 'subject_user', 'user_id', 'subject_id');
    }

    public function tasksAssigned()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function tasksGiven()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }

    public function addPoints(int $points): void
    {
        $this->points = max(0, (int) ($this->points ?? 0) + $points);
        $this->updateLevel();
        $this->save();
    }

    protected function updateLevel(): void
    {
        $p = (int) ($this->points ?? 0);

        if ($p <= 100) {
            $this->level = 'Bronze';
        } elseif ($p <= 300) {
            $this->level = 'Silver';
        } elseif ($p <= 600) {
            $this->level = 'Gold';
        } else {
            $this->level = 'Platinum';
        }
    }
}
