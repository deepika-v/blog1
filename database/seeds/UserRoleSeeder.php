<?php

use Illuminate\Database\Seeder;
use App\Models\Users\UserRole;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public $roles=[
        [
            "UserRole"=>"SuperUser",
        ],
        [
            "UserRole"=>"User",
        ]
        
    ];

    public function run()
    {

        foreach($this->roles as $role){
            $userroles= UserRole::where('user_role','=',$role['UserRole'])->get();
            $count= count($userroles);
            if($count == 0)
            {
                $now = date('Y-m-d H:i:s');
                $name=UserRole::create([
                    "user_role"=>$role["UserRole"],
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
            }
        }
    }
}
