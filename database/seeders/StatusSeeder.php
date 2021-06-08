<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Status;

class StatusSeeder extends Seeder
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
                'name' => 'New',
                'created_at' => now(),
            ],

            [
                'id' => 2,
                'name' => 'In Development',
                'created_at' => now(),
            ],

            [
                'id' => 3,
                'name' => 'Ready for Testing',
                'created_at' => now(),
            ],

            [
                'id' => 4,
                'name' => 'Done',
                'created_at' => now(),
            ],
        ];

        foreach ($items as $item) {
            Status::Create($item);
        }
    }
}
