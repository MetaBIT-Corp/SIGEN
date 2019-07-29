<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Docente;
use App\Estudiante;

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

        //Docentes agregados por Ricardo Estupinian
        Docente::create([
            'user_id'=>4,
            'carnet_dcn'=>'EL16002',
            'anio_titulo'=>2019,
            'activo'=>1,
            'tipo_jornada'=>1,
            'descripcion_docente'=>'Ingeniero de Sistemas Informaticos',
            'id_cargo_actual'=>1,
            'id_segundo_cargo'=>1,
            'nombre_docente'=>'Ricardo Estupinian'
        ]);
        Docente::create([
            'user_id'=>5,
            'carnet_dcn'=>'MT16007',
            'anio_titulo'=>2019,
            'activo'=>1,
            'tipo_jornada'=>1,
            'descripcion_docente'=>'Ingeniero de Sistemas Informaticos',
            'id_cargo_actual'=>1,
            'id_segundo_cargo'=>1,
            'nombre_docente'=>'Enrique Tejada'
        ]);
        Docente::create([
            'user_id'=>6,
            'carnet_dcn'=>'DC16009',
            'anio_titulo'=>2019,
            'activo'=>1,
            'tipo_jornada'=>1,
            'descripcion_docente'=>'Ingeniero de Sistemas Informaticos',
            'id_cargo_actual'=>1,
            'id_segundo_cargo'=>1,
            'nombre_docente'=>'Pablo Diaz'
        ]);

        //Estudiantes para pruebas agregado por Ricardo Estupinian
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

        Estudiante::create([
            'user_id'=>7,
            'carnet'=>'OG16003',
            'nombre'=>'Diego Ochoa',
            'activo'=>1,
            'anio_ingreso'=>2016
        ]);
        Estudiante::create([
            'user_id'=>8,
            'carnet'=>'FB16005',
            'nombre'=>'Jose Montano',
            'activo'=>1,
            'anio_ingreso'=>2016
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
