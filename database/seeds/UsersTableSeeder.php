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
            'email' => 'mt16007@gmail.com',
            'password' => bcrypt('sigen2019'),
            'role' => 1
        ]);
        User::create([
            'name' => 'Pablo',
            'email' => 'dc16009@gmail.com',
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
            'name' => 'Enrique Menjívar',
            'email' => 'mt16007@ues.edu.sv',
            'password' => bcrypt('sigen2019'),
            'role' => 2
        ]);
        User::create([
            'name' => 'René Martínez',
            'email' => 'mr11139@ues.edu.sv',
            'password' => bcrypt('sigen2019'),
            'role' => 2
        ]);
        User::create([
            'name' => 'Ricardo Estupinián',
            'email' => 'ricaslo@gmail.com',
            'password' => bcrypt('sigen2019'),
            'role' => 2
        ]);
        User::create([
            'name' => 'Pablo Díaz',
            'email' => 'dc16009@ues.edu.sv',
            'password' => bcrypt('sigen2019'),
            'role' => 2
        ]);
        User::create([
            'name' => 'Edwin Palacios',
            'email' => 'ap16014@ues.edu.sv',
            'password' => bcrypt('sigen2019'),
            'role' => 2
        ]);

        Estudiante::create([
            'id_est' => 1,
            'user_id'=>7,
            'carnet'=>'MT16007',
            'nombre'=>'Enrique Menjívar',
            'activo'=>1,
            'anio_ingreso'=>2016
        ]);
        Estudiante::create([
            'id_est' => 2,
            'user_id'=>8,
            'carnet'=>'MR11139',
            'nombre'=>'René Martínez',
            'activo'=>1,
            'anio_ingreso'=>2016
        ]);
        Estudiante::create([
            'id_est' => 3,
            'user_id'=>9,
            'carnet'=>'EL16002',
            'nombre'=>'Ricardo Bladimir',
            'activo'=>1,
            'anio_ingreso'=>2016
        ]);
        Estudiante::create([
            'id_est' => 4,
            'user_id'=>10,
            'carnet'=>'DC16009',
            'nombre'=>'Juan Pablo',
            'activo'=>1,
            'anio_ingreso'=>2016
        ]);
        Estudiante::create([
            'id_est' => 5,
            'user_id'=>11,
            'carnet'=>'AP16014',
            'nombre'=>'Edwin Joel',
            'activo'=>1,
            'anio_ingreso'=>2016
        ]);
        
    }
}
