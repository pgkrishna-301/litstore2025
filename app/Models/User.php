<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'user';

    protected $fillable = [
        'name',
        'mobile_number',
        'email',
        'password',
        'profile_image',
        'api_token',
        'hide',
        'architect',
        'target'   // ✅ Added target field
    ];

    protected $hidden = [
        'password',
        'api_token',
    ];

    /**
     * Automatically cast target JSON field to array.
     */
    protected $casts = [
        'target' => 'array',
    ];

    /**
     * Get the tasks for the user.
     */
    public function tasks()
    {
        return $this->hasMany(TaskManagement::class, 'user_id');
    }
}
