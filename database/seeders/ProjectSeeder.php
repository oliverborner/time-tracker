<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('projects')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'name' => 'Website Redesign 🔥',
                'description' => 'A more modern look and feel.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'name' => 'Mobile App [Flutter]',
                'description' => 'Cordova a no go? Lets flutter now',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'user_id' => 1,
                'name' => 'Marketing Campaign',
                'description' => 'Socialize ;-)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'user_id' => 1,
                'name' => 'Implementing a reusable tokenized Design System',
                'description' => 'That would be awesome!',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
