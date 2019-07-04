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

        //Estudiantes Para Pruebas
        User::create([
            'name' => 'Edwin Joel Amaya Palacios',
            'email' => 'ap16014@ues.edu.sv',
            'password' => bcrypt('sigen2019'),
            'role' => 1
        ]);
        User::create([
            'name' => 'Kilmer Fabricio Maravilla López',
            'email' => 'ml16006@ues.edu.sv',
            'password' => bcrypt('sigen2019'),
            'role' => 1
        ]);
        User::create([
            'name' => 'José Alberto Flores Barillas',
            'email' => 'fb16014@ues.edu.sv',
            'password' => bcrypt('sigen2019'),
            'role' => 1
        ]);

        User::create([
            'name' => 'Marco Antonio Flores Ventura',
            'email' => 'fv16002@ues.edu.sv',
            'password' => bcrypt('sigen2019'),
            'role' => 1
        ]);
        User::create([
            'name' => 'Armando Patricio Rivera López',
            'email' => 'rv16007@ues.edu.sv',
            'password' => bcrypt('sigen2019'),
            'role' => 1
        ]);
        User::create([
            'name' => 'Carlos Rene Martinez Rivera',
            'email' => 'mr11139@ues.edu.sv',
            'password' => bcrypt('sigen2019'),
            'role' => 1
        ]);
    }
}
