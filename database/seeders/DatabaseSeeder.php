<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      //   \App\Models\User::factory(150)->create();

     /*    \App\Models\User::factory()->create([
             'name' => 'pinguim',
             'email' => 'pt@dl.com',
             'active' => true,
         ]);*/

      //   \App\Models\TaskGroup::factory(5)->create();
         \App\Models\Task::factory(200)->create();
    }
}
