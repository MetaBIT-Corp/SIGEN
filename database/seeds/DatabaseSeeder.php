<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
        	UsersTableSeeder::class,
            MateriaTableSeeder::class,
            CicloTableSeeder::class,
            MateriaCicloTableSeeder::class,
            GrupoCargaTableSeeder::class,
            CargaAcademicaTableSeeder::class,
            DetalleInscripcionTableSeeder::class,
        	TipoItemTableSeeder::class,
            AreaTableSeeder::class,
            GrupoEmparejamientoTableSeeder::class,
            PreguntaTableSeeder::class,
            EncuestaTableSeeder::class,
            OpcionTableSeeder::class,
            EvaluacionTableSeeder::class,
            TurnoTableSeeder::class,
            ClaveTableSeeder::class,
            ClaveAreaTableSeeder::class,
            ClaveAreaPreguntaTableSeeder::class,
        ]);
    }
}
