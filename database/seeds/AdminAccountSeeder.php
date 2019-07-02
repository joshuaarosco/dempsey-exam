<?php

use App\Laravel\Models\User;
use Illuminate\Database\Seeder;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $existing_super_user = User::where('username', "executive")->first();
        if(!$existing_super_user) {
            User::create(['fname' => "Super",'lname' => "User",'contact_number' => "09222222222" , 'type' => "super_user", 'username' => "executive", 'email' => "ceo@domain.com", 'password' => bcrypt('executive')]);
        }

        $existing_super_user = User::where('username', "admin")->first();
        if(!$existing_super_user) {
            User::create(['fname' => "Super",'lname' => "User",'contact_number' => "09222222222" , 'type' => "super_user", 'username' => "admin", 'email' => "admin@domain.com", 'password' => bcrypt('admin')]);
        }
    }
}
