<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'id' => 1,
                'name' => 'Manager',
                'created_at' => now(),
            ],

            [
                'id' => 2,
                'name' => 'Engineer',
                'created_at' => now(),
            ],

            [
                'id' => 3,
                'name' => 'Quality Assurance',
                'created_at' => now(),
            ],
        ];

        foreach ($items as $item) {
            Role::Create($item);
        }
    }
}
