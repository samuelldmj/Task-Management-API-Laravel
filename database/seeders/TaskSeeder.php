<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Set start date to the first day of the month, three months ago
        $start = now()->startOfMonth()->subMonthsNoOverflow();

        // Set end date to current date
        $end = now();

        // Create a date period from start to end, incrementing by 1 day
        $period = CarbonPeriod::create($start, '1 day', $end);

        // Create 5 users using factory
        User::factory(5)->create()->each(function ($user) use ($period) {
            // Loop through each date in the period
            foreach ($period as $date) {
                // Randomize the time for the date
                $date->hour(rand(0, 23))->minute(rand(0, 59))->second(rand(0, 59));

                // Create random number (1-5) of tasks for each date
                Task::factory(rand(1, 5))->create([
                    'user_id' => $user->id,        // Assign task to current user
                    'created_at' => $date,         // Set creation date to loop date
                    'updated_at' => $date,         // Set update date to loop date
                ]);
            }
        });
    }
}
