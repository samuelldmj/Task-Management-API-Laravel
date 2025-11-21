<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'name',
        'task_priority_id',
        'is_completed'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function priority()
    {
        return $this->belongsTo(TaskPriority::class, 'task_priority_id');
    }


    public function scopeHandleSort(Builder $query, ?string $column)
    {
        $query->when($column === 'name', function ($query) {
            $query->orderBy('name', 'asc');
        })
            ->when($column === 'time', function ($query) {
                $query->orderBy('created_at', 'asc');
            })
            ->when($column === 'priority', function ($query) {
                $query->orderByRaw('ISNULL(task_priority_id), task_priority_id ASC');
            });
    }
}
