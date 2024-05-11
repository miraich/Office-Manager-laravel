<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::insert([
            [
                'name' => 'General Director',
            ],
            [
                'name' => 'Department Head',
            ],
            [
                'name' => 'Project Manager',
            ],
            [
                'name' => 'Employee',
            ]
        ]);
        DB::table('statuses')->insert([
            [
                'name' => 'inactive'
            ],
            [
                'name' => 'in_progress'
            ],
            [
                'name' => 'done'
            ],
        ]);
    }
}
