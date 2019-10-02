<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Clave_Area_Pregunta_Estudiante;
use App\Intento;
use App\Clave;

class Intento extends Model
{
    protected $table='intento';
    
    protected $fillable = [
    	'estudiante_id','clave_id','fecha_inicio_intento','fecha_final_intento','nota_intento'
    ];

    //Relaciones

    //Un intento tiene muchas respuestas
    public function respuestas(){
    	return $this->hasMany(Respuesta::class, 'id_intento', 'id');
    }

    public function estudiante(){
    	return $this->belongsTo(Estudiante::class, 'estudiante_id', 'id_est');
    }

    public function clave(){
        return $this->belongsTo(Clave::class);
    }


    /*-----------------------------Metodos------------------------------------*/
    //Función para calcular la nota del intento
    public function calcularNota($intento_id){
        $intento = Intento::find($intento_id); //Obtener el intento
        $estudiante_id = $intento->estudiante->id_est; //Obtener al estudiante que realizó el intento
        $numero_intento = $intento->numero_intento; //Obtiene el numero de intento actual
        $nota = 0.0;
        $i=0;

        //dd($intento->respuestas);
        foreach($intento->respuestas as $respuesta){

            //Obtener la pregunta a la que pertenece la respuesta
            $pregunta_id = $respuesta->pregunta->id;

            //Consulta para obtener el objeto clave_area_pregunta_estudiante al que pertenece la pregunta
            $cape = Clave_Area_Pregunta_Estudiante::where('estudiante_id', $estudiante_id)
                                                    ->where('pregunta_id', $pregunta_id)
                                                    ->first();
    
            //Obtener la clave_aera a la que pertenece la pregunta
            $clave_area = $cape->clave_area;

            //Obtener la modalidad a la que pertecene la pregunta
            $modalidad = $clave_area->area->tipo_item_id;

            //Obtener el peso de la pregunta
            $peso = $clave_area->peso;

            //Recupera la cantidad de preguntas por estudiante en el intento actual del clave_area indicado
            $cape_cantidad = Clave_Area_Pregunta_Estudiante::where('estudiante_id', $estudiante_id)
                                                    ->where('clave_area_id', $clave_area->id)
                                                    ->where('numero_intento', $numero_intento)
                                                    ->get();

            //dd(count($cape_cantidad), $estudiante_id, $clave_area->id, $numero_intento);
            //Cuenta la cantidad de preguntas que tiene el objeto clave_are
            $cantidad_preguntas = count($cape_cantidad);

            //Si la respuesta que seleccionó en la pregunta es correcta
            if($respuesta->id_opcion != null){
                if($respuesta->opcion->correcta==1){
                    
                   //Calcula la ponderación de la pregunta
                    $nota += ($peso/$cantidad_preguntas)/10;
                }
            }else{
                //Verifica si la pregunta pertenece a modalidad de respuesta corta
                if($modalidad==4){
                    $txt_respuesta = strtolower($respuesta->texto_respuesta);
                    $txt_opcion = strtolower($respuesta->pregunta->opciones[0]->opcion);
                    
                    //Compara la respuesta del usuario con la respuesta correcta
                    if(strcmp($txt_respuesta, $txt_opcion) == 0){
                        
                        //Calcula la ponderación de la pregunta
                        $nota += ($peso/$cantidad_preguntas)/10;
                    }
                }

            }
        }

        return $nota;
        
    }
}