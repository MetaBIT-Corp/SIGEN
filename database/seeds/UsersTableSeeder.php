<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
    		'name' => 'Admin',
    		'email' => 'admin@gmail.com',
    		'password' => bcrypt('sigen2019'),
    		'role' => 0
    	]);

    	User::create([
    		'name' => 'Docente',
    		'email' => 'docente@gmail.com',
    		'password' => bcrypt('sigen2019'),
    		'role' => 1
    	]);

    	User::create([
    		'name' => 'Estudiante',
    		'email' => 'estudiante@gmail.com',
    		'password' => bcrypt('sigen2019'),
    		'role' => 2
    	]);
    }
}
