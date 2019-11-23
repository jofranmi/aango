<?php

use Illuminate\Database\Seeder;

class CreateOrderStatuses extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            ['name' => 'Pending'],
            ['name' => 'Processing'],
            ['name' => 'Assigned'],
            ['name' => 'Shipped'],
            ['name' => 'Completed'],
            ['name' => 'Cancelled']
        ]);
    }
}
