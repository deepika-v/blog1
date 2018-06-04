<?php

use Illuminate\Database\Seeder;
use App\Model\Users\UserRole;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserRole::create(
        	['user_role'     => 'SuperUser'],
	        ['user_role'	    =>  'User']
	         
	    ));
    }
}
