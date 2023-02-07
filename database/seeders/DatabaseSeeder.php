<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Tasks;
use App\Models\Status;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Role::create([
            'name' => 'Admin',
        ]);

        Role::create([
            'name' => 'User',
        ]);

        Status::create([
            'name' => 'pending',
        ]);
        Status::create([
            'name' => 'in progress',
        ]);
        Status::create([
            'name' => 'completed',
        ]);

        User::create(
            [
                'name' => "Admin",
                'email' => 'test1@test.com',
                'password'=> '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'role_id' => 1,
            ]
        );

        User::create(
            [
                'name' => "User",
                'email' => 'test@test.com',
                'password'=> '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'role_id' => 2,
            ]
        );

        Tasks::create(
            [
                'title' => "Task 1",
                'description' => 'test',
                'due_date' => '2023-05-05',
                'status_id' => 1,
            ],
        );

        Tasks::create(
            [
                'title' => "Task 2",
                'description' => 'test',
                'due_date' => '2023-05-05',
                'status_id' => 1,
            ],
        );


    }
}
