<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'fullname',
        'email',
        'password',
        'role',
        'farm_id',
        'phone_number',
        'phone',
        'address',
        'picture',
        'avatar',
        'status',
        'is_active',
        'email_verified',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_verified' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $dates = [
        'deleted_at',
    ];

    // Relations
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function managedFarms()
    {
        return $this->hasMany(Farm::class, 'manager_id');
    }

    // Relations supplémentaires
    public function createdHarvests()
    {
        return $this->hasMany(Harvest::class, 'created_by_id');
    }

    public function updatedHarvests()
    {
        return $this->hasMany(Harvest::class, 'updated_by_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function createdTasks()
    {
        return $this->hasMany(FarmTask::class, 'created_by_id');
    }

    public function assignedTasks()
    {
        return $this->hasMany(FarmTask::class, 'assigned_to_id');
    }

    public function completedTasks()
    {
        return $this->hasMany(FarmTask::class, 'completed_by_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'created_by_id');
    }

    public function livestockStockMovements()
    {
        return $this->hasMany(LivestockStockMovement::class, 'created_by_id');
    }

    public function notifications()
    {
        return $this->hasMany(InternalNotification::class);
    }

    public function createdNotifications()
    {
        return $this->hasMany(InternalNotification::class, 'created_by_id');
    }
}
