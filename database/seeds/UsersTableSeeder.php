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

        //Docentes Para Pruebas
        User::create([
            'name' => 'Ricardo',
            'email' => 'el16002@ues.edu.sv',
            'password' => bcrypt('sigen2019'),
            'role' => 1
        ]);
        User::create([
            'name' => 'Enrique',
            'email' => 'mt16007@ues.edu.sv',
            'password' => bcrypt('sigen2019'),
            'role' => 1
        ]);
        User::create([
            'name' => 'Pablo',
            'email' => 'dc16009@ues.edu.sv',
            'password' => bcrypt('sigen2019'),
            'role' => 1
        ]);

        //Estudiantes para pruebas
        User::create([
            'name' => 'Jose',
            'email' => 'jose@gmail.com',
            'password' => bcrypt('sigen2019'),
            'role' => 2
        ]);
        User::create([
            'name' => 'Diego',
            'email' => 'diego@gmail.com',
            'password' => bcrypt('sigen2019'),
            'role' => 2
        ]);
    }
}
