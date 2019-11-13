<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Area;
use App\Clave_Area;
use App\Clave_Area_Pregunta;
use App\Clave;
use App\Pregunta;
use App\Opcion;


class ClaveController extends Controller
{

    //Funcion para cargar las preguntas de una área mediante AJAX
    public function preguntasPorArea($id){

    	$clave_area = Clave_Area::where('id', $id)->first();
        $area = Area::where('id',$clave_area->area_id)->first();

        $cap = Clave_Area_Pregunta::where('clave_area_id',$id)->pluck('pregunta_id');

        $preguntas = [];

        if($area->tipo_item_id!=2){
    
            $preguntas_area = DB::table('area')
                            ->where('area.id', $clave_area->area_id)
                            ->join('grupo_emparejamiento as grupo', 'area.id', '=', 'grupo.area_id')
                            ->join('pregunta as p', 'grupo.id', '=', 'p.grupo_emparejamiento_id')
                            ->select('p.id', 'p.pregunta', 'area.titulo')
                            ->get();

            foreach ($preguntas_area as $pregunta) {

                $opciones = Opcion::where('pregunta_id',$pregunta->id)->count();

                if($area->id_pdg_dcn!=null){
                    if($opciones>=2){
                        array_push($preguntas,$pregunta);
                    }
                }else{
                    if($opciones>=3){
                        array_push($preguntas,$pregunta);
                    }
                }
                
            }
        }else{
            $preguntas = DB::table('area')
                            ->where('area.id', $clave_area->area_id)
                            ->join('grupo_emparejamiento as grupo', 'area.id', '=', 'grupo.area_id')
                            ->join('pregunta as p', 'grupo.id', '=', 'p.grupo_emparejamiento_id')
                            ->select('p.id', 'p.pregunta', 'area.titulo')
                            ->get();
        }

    	$data = ['p_asignadas'=>$cap, 'preguntas'=>$preguntas];
    	return $data;

    }

    //Funcion para cargar las preguntas asignadas a una clave
    public function preguntasAgregadas($id){

        $preguntas_asginadas = DB::table('clave_area_pregunta as cap')
                                    ->where('cap.clave_area_id', $id)
                                    ->join('pregunta as p', 'p.id', '=', 'cap.pregunta_id')
                                    ->select('p.pregunta')
                                    ->get();

        return $preguntas_asginadas;
    }

    //Función para asignar a la clave las preguntas seleccionadas del área
    public function asignarPreguntas(Request $request){

        $preguntasEmp = $request->input('preguntasEmp');
        $preguntas = $request->input('preguntas');
    	$id_clave_area = $request->input('clave_area');
        $modalidad = $request->input('modalidad');
    	$mensaje = 'Ninguna pregunta fue seleccionada';
    	$notificacion = 'error';

    	//Almacenando preguntas en la base de datos
    	if($modalidad){
            if($preguntasEmp){
                DB::table('clave_area_pregunta')->where('clave_area_id', $id_clave_area)->delete();

                foreach ($preguntasEmp as $preguntaEmp) {
                    $preguntas = Pregunta::where('grupo_emparejamiento_id',$preguntaEmp)->get();

                    foreach ($preguntas as $pregunta) {
                        $clave_area_pregunta = new Clave_Area_Pregunta();
                        $clave_area_pregunta->clave_area_id = $id_clave_area;
                        $clave_area_pregunta->pregunta_id = $pregunta->id;

                        $clave_area_pregunta->save();
                    }
                }
                $mensaje = 'Preguntas agregadas exitosamente.';
                $notificacion = 'exito';
            }
        }else{
            if($preguntas){
                DB::table('clave_area_pregunta')->where('clave_area_id', $id_clave_area)->delete();
                foreach ($preguntas as $pregunta) {
                    $clave_area_pregunta = new Clave_Area_Pregunta();
                    $clave_area_pregunta->clave_area_id = $id_clave_area;
                    $clave_area_pregunta->pregunta_id = $pregunta;

                    $clave_area_pregunta->save();
                }
                $mensaje = 'Preguntas agregadas exitosamente.';
                $notificacion = 'exito';
            }
        }
    	

    	return back()->with($notificacion, $mensaje);
    }

    //Funcion para editar datos de la área asignada a la clave
    public function editarClaveArea(Request $request){
        $id_clave_area = $request->input('id_clave_area');
        $peso_total = $request->input('peso_total');
        $clave_area = Clave_Area::find($id_clave_area);
        $notificacion = 'exito';
        $mensaje = 'Los datos fueron modifcados con éxito';

        $rules = [
            'numero_preguntas' => 'required|integer|min:1|max:20',
            'peso' => 'integer|min:0|max:100|required'
        ];

        $messages = [
            'numero_preguntas.min' => 'Debe tomar al menos una pregunta del área',
            'numero_preguntas.max' => 'No se puede asignar mas de 20 preguntas de una área a la clave',
            'numero_preguntas.required' => 'Debe ingresar el número de preguntas a tomar del área',
            'numero_preguntas.integer' => 'La cantidad de preguntas debe ser un dato entero',
            'peso.min' => 'El peso del área no puede ser negativo',
            'peso.max' => 'No se puede asignar un peso mayor a 100%',
            'peso.required' => 'Debe ingresar el peso que tendrá el área',
            'peso.integer' => 'El peso debe ser un dato entero'
        ];

        $this->validate($request, $rules, $messages);

        //Pregunta si el peso ingresado + el peso total actual de turno es mayor a 100
        if($request->input('peso') - $clave_area->peso + $peso_total > 100){
            $notificacion='error';
            $mensaje='El peso del turno no puede ser mayor a 100';
        }else{
            $clave_area->numero_preguntas = $request->input('numero_preguntas');
            $clave_area->peso = $request->input('peso');

            $clave_area->save();
        }

        return back()->with($notificacion, $mensaje);

    }

    //Funcíón para eliminar la área que se le ha asignado a la clave
    public function eliminarClaveArea(Request $request){
        $id_clave_area = $request->input('id_clave_area');
        $clave_area = Clave_Area::find($id_clave_area);
        $notificacion = 'exito';
        $mensaje = 'El área ha sido eliminada de la clave';

        //Verificar si el objeto ya está siendo utilizado
        $preguntas_utilizadas = DB::table('clave_area_pregunta as ca')
                            ->where('clave_area_id', $id_clave_area)
                            ->join('respuesta as r', 'r.id_pregunta', '=', 'ca.pregunta_id')
                            ->get();

        if(count($preguntas_utilizadas)){
            $notificacion = 'error';
            $mensaje = 'El área no puede eliminarse porque ya está siendo utilziada';
        }else{
            DB::table('clave_area_pregunta')->where('clave_area_id', $id_clave_area)->delete();
            $clave_area->delete();
        }

        return back()->with($notificacion, $mensaje);
    }

    //Funcion para cargar las preguntas de una área de emparejamiento mediante AJAX
    public function preguntasPorAreaEmp($id){
        $clave_area = Clave_Area::where('id', $id)->first();

        $cap = DB::table('clave_area_pregunta as cap')
                    ->where('clave_area_id', $id)
                    ->join('pregunta as p', 'p.id', '=', 'cap.pregunta_id')
                    ->join('grupo_emparejamiento as grupo', 'grupo.id', '=', 'p.grupo_emparejamiento_id')
                    ->select('grupo.id')
                    ->get();

        $preguntas = DB::table('area')
                        ->where('area.id', $clave_area->area_id)
                        ->where('area.tipo_item_id', 3)
                        ->join('grupo_emparejamiento as grupo', 'area.id', '=', 'grupo.area_id')
                        ->select('grupo.id', 'grupo.descripcion_grupo_emp', 'area.titulo')
                        ->get();

        $data = ['p_asignadas'=>$cap, 'preguntas'=>$preguntas];
        return $data;

    }

    //Funcion para cargar las preguntas de una area de emparejamiento asignadas a una clave
    public function preguntasAgregadasEmp($id){

        $area = Clave_Area::find($id);
        $preguntas_asginadas = DB::table('clave_area_pregunta as cap')
                                    ->where('cap.clave_area_id', $id)
                                    ->join('pregunta as p', 'p.id', '=', 'cap.pregunta_id')
                                    ->join('grupo_emparejamiento as grupo', 'grupo.id', '=', 'p.grupo_emparejamiento_id')
                                    ->select('grupo.descripcion_grupo_emp')
                                    ->distinct()
                                    ->get();

        return $preguntas_asginadas;
    }

    //Funcion AJAX para para validar que el peso del turno no sobrepase a 100
    public function validarPeso($id_clave_area){
        $clave_area = Clave_Area::find($id_clave_area);
        $clave = $clave_area->clave;
        $peso = 0.0;

        foreach ($clave->clave_areas as $ca) {
            $peso += $ca->peso;
        }

        return ($peso);
    }

}
