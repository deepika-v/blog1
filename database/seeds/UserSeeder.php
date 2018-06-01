<?php

use Illuminate\Database\Seeder;
use App\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         User::create(array(
	        'name'     => 'test',
	        'email' => 'test@test.com',
	        'password' => Hash::make('123456'),
	    ));
    }
}
