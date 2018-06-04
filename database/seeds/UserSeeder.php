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
            'id'  =>'100001',
	        'name'     => 'test',
	        'email' => 'superuser@test.com',
	        'password' => Hash::make('123456'),
            'user_role_id'=>'1'
	    ));
    }
}
