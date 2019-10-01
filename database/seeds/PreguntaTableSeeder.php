<?php

use Illuminate\Database\Seeder;
use App\Pregunta;
use App\Grupo_Emparejamiento;
class PreguntaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
        //Grupo emparejamiento 1
        Pregunta::create([
            'grupo_emparejamiento_id'=>1,
            'pregunta'=>'Cuantos departamentos tiene El Salvador'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>1,
            'pregunta'=>'Cuantos municipios tiene  El Salvador'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>1,
            'pregunta'=>'Cual es la capital de El Salvador'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>1,
            'pregunta'=>'Cual es el departamento más grande de El Salvador'
        ]);

        //Grupo emparejamiento 2
        Pregunta::create([
            'grupo_emparejamiento_id'=>2,
            'pregunta'=>'A que empresa pertenece Red Hat'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>2,
            'pregunta'=>'A que empresa pertenece Android'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>2,
            'pregunta'=>'A que empresa pertenece iOS'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>2,
            'pregunta'=>'A que empresa pertenece windows phone'
        ]);

        //Opción múltiple
        Pregunta::create([
            'grupo_emparejamiento_id'=>3,
            'pregunta'=>'Cual de los siguientes no es un GIS'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>4,
            'pregunta'=>'Cual es la materia más difícl de la EISI'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>5,
            'pregunta'=>'Cual es el IDE oficial para programar en Android'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>6,
            'pregunta'=>'En cual de estos IDE se puede programar para iOS'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>7,
            'pregunta'=>'Cual de estos es un sistema'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>8,
            'pregunta'=>'Se refiere a la facilidad para usar el software'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>9,
            'pregunta'=>'Cual de estos no es una metodología ágil'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>10,
            'pregunta'=>'Cual de estas cuentas no se incluye en el balance general'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>11,
            'pregunta'=>'Quien es el padre de la geología'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>12,
            'pregunta'=>'Cual es la proyeccion que se utiliza en El Salvador'
        ]);

        //Verdadero falso
        Pregunta::create([
            'grupo_emparejamiento_id'=>13,
            'pregunta'=>'Historia de usuario se refiere a la descripcion de una funcionalidad que debe incorporar un sistema de software'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>14,
            'pregunta'=>'En una organizacion inteligente, el aprendizaje en equipo es la disciplina que constituye el elemento principal de interconexion con las otras'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>15,
            'pregunta'=>'La administracion es escencial en todas corporaciones organizadas, asi como en todos los niveles de la organizacion'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>16,
            'pregunta'=>'La administración es un arte'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>17,
            'pregunta'=>'La administración es una ciencia'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>18,
            'pregunta'=>'El elemento más valioso de una empresa es el capital que esta tiene'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>19,
            'pregunta'=>'Google maps es un GIS'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>20,
            'pregunta'=>'Adroid Studio fue lanzado en el 2012'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>21,
            'pregunta'=>'La siguiente ip 192.160.4.100 es una ip pública'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>22,
            'pregunta'=>'GNS3 es un simulador de redes'
        ]);

        //Respuesta corta
        Pregunta::create([
            'grupo_emparejamiento_id'=>23,
            'pregunta'=>'Cual es el buscador más utilizado en la web'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>24,
            'pregunta'=>'Cual es la red social más utilizada'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>25,
            'pregunta'=>'Cual es la distribución de linux con la comunidad más grande'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>26,
            'pregunta'=>'Cual es el nombre de la capa 3 en el modelo OSI'
        ]);
        Pregunta::create([
            'grupo_emparejamiento_id'=>27,
            'pregunta'=>'Cual es el sistema operativo más utilizado en los servidores'
        ]);


    }
}
