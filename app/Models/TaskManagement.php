<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskManagement extends Model
{
    use HasFactory;
     protected $table = 'task_managements';

    protected $fillable = [
        'title',
        'description',
        'assignment_to',
        'assignment_by',
        'due_date',
        'start_date',
        'importance_scale',
        'status',
        'reminder_set',
        'user_id',
    ];

    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
