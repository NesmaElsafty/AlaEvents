<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 random events
        Event::factory()->count(20)->create();

        // Create 5 active events
        Event::factory()->count(5)->active()->create();

        // Create 3 inactive events
        Event::factory()->count(3)->inactive()->create();
    }
}
