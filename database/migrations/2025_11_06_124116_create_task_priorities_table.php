<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_priorities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description');
            $table->timestamps();
        });

        DB::table('task_priorities')->insert([
            [
                'name' => 'low',
                'description' => 'Low priority',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'medium',
                'description' => 'Medium priority',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'high',
                'description' => 'High priority',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_priorities');
    }
};
