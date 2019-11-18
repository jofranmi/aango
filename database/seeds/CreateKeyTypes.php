<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CreateKeyTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            ['name' => 'Brass Key'],
            ['name' => 'Smart Key'],
            ['name' => 'Remote Key'],
            ['name' => 'Special Order Key'],
            ['name' => 'Transponder Key'],
        ]);
    }
}
