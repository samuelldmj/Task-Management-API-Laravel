<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        ];
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }


    /**
     * Get a summary of tasks for the user based on the specified period.
     *
     * @param string|null $period The period for which to retrieve tasks. Options:
     * - 'today': Tasks created today.
     * - 'yesterday': Tasks created yesterday.
     * - 'last-week': Tasks created in the previous week (Monday to Sunday).
     * - 'this-month': Tasks created in the current month.
     * - 'last-month': Tasks created in the previous month.
     * - null (default): Tasks created in the current month (changed from last 7 days to include more data).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function taskSummary($period = null)
    {
        $absoluteMinDate = now()->subYears(50);

        [$start, $end] = match ($period) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'yesterday' => [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()],
            'last-week' => [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()],
            'this-month' => [now()->startOfMonth(), now()->endOfMonth()],
            'last-month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'all-time' => [$absoluteMinDate, now()],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };

        return $this->tasks()
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();
    }
}
