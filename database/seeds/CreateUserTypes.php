<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CreateUserTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_types')->insert([
            ['name' => 'admin'],
            ['name' => 'office'],
            ['name' => 'customer']
        ]);
    }
}
