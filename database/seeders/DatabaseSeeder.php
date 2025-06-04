<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Grant;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        
      /*  User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
        $this->call(UserTableSeeder::class);
        $this->call(StudentTableSeeder::class);
        $this->call(GrantTableSeeder::class);
    }
}
