<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\User;
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
        Subscription::insert([
            [
                'name' => 'Базовая',
                'price' => 0,
                'description' => 'Бесплатная подписка с минимальным функционалом',
                'max_people' => 5,
            ],
            [
                'name' => 'Расширенная',
                'price' => 500,
                'description' => 'Улучшенная версия подписки с большим функционалом, количеством людей в команде',
                'max_people' => 15,
            ],
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
        $user = User::factory()
            ->has(Project::factory()->count(3),'projects')
            ->create();
        $user = User::factory()->create();
    }
}
