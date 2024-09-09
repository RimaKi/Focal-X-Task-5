<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_tasks';
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'assigned_to'
    ];
    protected $guarded = [
        'created_by'
    ];

    public $timestamps = true;
    const CREATED_AT = 'create';
    const UPDATED_AT = 'update';


    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'uuid');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'uuid');
    }

    // accessors
    public function getDueDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

}
