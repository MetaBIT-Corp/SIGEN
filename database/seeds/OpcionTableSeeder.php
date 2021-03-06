<?php

use Illuminate\Database\Seeder;
use App\Opcion;
class OpcionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//Grupo emparejamiento 1
        Opcion::create([
        	'pregunta_id'=>1,
        	'opcion'=>'20',
        	'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>1,
            'opcion'=>'14',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>2,
            'opcion'=>'262',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>2,
            'opcion'=>'1000',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>3,
            'opcion'=>'San Salvador',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>3,
            'opcion'=>'Soya',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>4,
            'opcion'=>'San miguel',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>4,
            'opcion'=>'Usulutan',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>4,
            'opcion'=>'La unión',
            'correcta'=>false
        ]);

        //Grupo emparejamiento 2
        Opcion::create([
            'pregunta_id'=>5,
            'opcion'=>'Linux foundation',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>5,
            'opcion'=>'IBM',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>6,
            'opcion'=>'Google',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>6,
            'opcion'=>'Android Inc',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>7,
            'opcion'=>'Steve Jonbs foundation',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>7,
            'opcion'=>'Apple',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>8,
            'opcion'=>'Git Hub',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>8,
            'opcion'=>'Microsoft',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>8,
            'opcion'=>'Microshop',
            'correcta'=>false
        ]);


        //Opción múltiple
        Opcion::create([
            'pregunta_id'=>9,
            'opcion'=>'OpenJump',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>9,
            'opcion'=>'Google maps',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>9,
            'opcion'=>'Ilwis',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>9,
            'opcion'=>'SpringGis',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>10,
            'opcion'=>'Ingeniería económica',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>10,
            'opcion'=>'Herramientas de productividad',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>10,
            'opcion'=>'Sistema de información geográfica',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>10,
            'opcion'=>'Seguridad informática',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>11,
            'opcion'=>'Eclipse',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>11,
            'opcion'=>'Netbeans',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>11,
            'opcion'=>'Android studio',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>11,
            'opcion'=>'Todas',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>12,
            'opcion'=>'IntelliJ IDEA',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>12,
            'opcion'=>'Xcode',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>12,
            'opcion'=>'Android studio',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>12,
            'opcion'=>'Eclipse',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>13,
            'opcion'=>'Un carro',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>13,
            'opcion'=>'Un planeta',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>13,
            'opcion'=>'Un árbol',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>13,
            'opcion'=>'Todas',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>14,
            'opcion'=>'Eficiencia',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>14,
            'opcion'=>'Mantenibilidad',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>14,
            'opcion'=>'Usabilidad',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>14,
            'opcion'=>'Operabilidad',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>15,
            'opcion'=>'En cascada',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>15,
            'opcion'=>'RUP',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>15,
            'opcion'=>'XP',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>15,
            'opcion'=>'SCRUM',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>16,
            'opcion'=>'Efectivo',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>16,
            'opcion'=>'Cuentas por pagar',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>16,
            'opcion'=>'Ingresos por servicio',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>16,
            'opcion'=>'Mobiliario',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>17,
            'opcion'=>'Newton',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>17,
            'opcion'=>'Aristoteles',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>17,
            'opcion'=>'Eratostenes',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>17,
            'opcion'=>'Pitágoras',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>18,
            'opcion'=>'UTM',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>18,
            'opcion'=>'Conforme de Lambert',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>18,
            'opcion'=>'Conforme cónica de Lambert',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>18,
            'opcion'=>'Cónica conforme de Lambert',
            'correcta'=>true
        ]);

        //Verdadero y false
        Opcion::create([
            'pregunta_id'=>19,
            'opcion'=>'Verdadero',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>19,
            'opcion'=>'Falso',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>20,
            'opcion'=>'Verdadero',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>20,
            'opcion'=>'Falso',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>21,
            'opcion'=>'Verdadero',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>21,
            'opcion'=>'Falso',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>22,
            'opcion'=>'Verdadero',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>22,
            'opcion'=>'Falso',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>23,
            'opcion'=>'Verdadero',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>23,
            'opcion'=>'Falso',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>24,
            'opcion'=>'Verdadero',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>24,
            'opcion'=>'Falso',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>25,
            'opcion'=>'Verdadero',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>25,
            'opcion'=>'Falso',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>26,
            'opcion'=>'Verdadero',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>26,
            'opcion'=>'Falso',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>27,
            'opcion'=>'Verdadero',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>27,
            'opcion'=>'Falso',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>28,
            'opcion'=>'Verdadero',
            'correcta'=>false
        ]);
        Opcion::create([
            'pregunta_id'=>28,
            'opcion'=>'Falso',
            'correcta'=>true
        ]);

        //Resouesta corta
        Opcion::create([
            'pregunta_id'=>29,
            'opcion'=>'Google',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>30,
            'opcion'=>'Facebook',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>31,
            'opcion'=>'Debian',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>32,
            'opcion'=>'Red',
            'correcta'=>true
        ]);
        Opcion::create([
            'pregunta_id'=>33,
            'opcion'=>'CentOS',
            'correcta'=>true
        ]);

        //Emparejamiento extra

         Opcion::create([
            'pregunta_id'=>34,
            'opcion'=>'0',
            'correcta'=>true
        ]);

          Opcion::create([
            'pregunta_id'=>34,
            'opcion'=>'8x',
            'correcta'=>false
        ]);

          Opcion::create([
            'pregunta_id'=>35,
            'opcion'=>'1',
            'correcta'=>true
        ]);

         Opcion::create([
            'pregunta_id'=>35,
            'opcion'=>'x al cuadrado',
            'correcta'=>false
        ]);

           Opcion::create([
            'pregunta_id'=>36,
            'opcion'=>'x',
            'correcta'=>true
        ]);
            Opcion::create([
            'pregunta_id'=>36,
            'opcion'=>'x al cubo',
            'correcta'=>false
        ]);

            Opcion::create([
            'pregunta_id'=>37,
            'opcion'=>'8x',
            'correcta'=>true
        ]);

        Opcion::create([
            'pregunta_id'=>37,
            'opcion'=>'0',
            'correcta'=>false
        ]);

             Opcion::create([
            'pregunta_id'=>38,
            'opcion'=>'x al cuadrado',
            'correcta'=>true
        ]);

             Opcion::create([
            'pregunta_id'=>38,
            'opcion'=>'1',
            'correcta'=>false
        ]);


              Opcion::create([
            'pregunta_id'=>39,
            'opcion'=>'x al cubo',
            'correcta'=>true
        ]);
               Opcion::create([
            'pregunta_id'=>39,
            'opcion'=>'x',
            'correcta'=>false
        ]);

    }
}
