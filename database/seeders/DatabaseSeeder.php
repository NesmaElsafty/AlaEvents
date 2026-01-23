<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the single admin user
        User::firstOrCreate(
            ['email' => 'admin@alaevents.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('123456789'), // Change this password after first login
            ]
        );

        // Seed events
        $this->call([
            EventSeeder::class,
        ]);
    }
}
