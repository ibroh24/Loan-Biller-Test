<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'ibroh24@gmail.com',
            'firstname'=> 'Ibrahim',
            'lastname'=> 'Hammed',
            'password'=> bcrypt('secret-password'),
            'phone' => '07063543872'
        ]);

        User::create([
            'email' => 'demo@gmail.com',
            'firstname'=> 'Demo',
            'lastname'=> 'Junior',
            'password'=> bcrypt('secret-password'),
            'phone' => '07063543872'
        ]);
    }
}
