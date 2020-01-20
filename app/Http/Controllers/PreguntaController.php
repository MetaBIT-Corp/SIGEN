<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Pregunta;
use App\Area;
use App\Grupo_Emparejamiento;
use App\Opcion;
class PreguntaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id_area, Request $request)
    {
        $area=Area::find($id_area);
        $gpos=$area->grupos_emparejamiento;
        if($request->ajax()){
            $i=1;
            if($request->id_gpo==1){

              $grupos=[];

              foreach ($gpos as $gpo) {
                $grupos[]=[
                  'num'=>$i++,
                  'id'=>$gpo->id,
                  'descripcion_grupo_emp'=>$gpo->descripcion_grupo_emp,
                  'area_id'=>$gpo->area_id
                ];
              }
                return dataTables()
                    ->of($grupos)
                    ->addColumn('actions','pregunta/actions')
                    ->rawColumns(['actions'])
                    ->toJson();
            }else{
                $pregunta=[];
                foreach ($gpos as $gpo) {
                    $preg=[
                      'num'=>$i++,
                      'id'=>$gpo->preguntas[0]->id,
                      'pregunta'=>$gpo->preguntas[0]->pregunta,
                      'grupo_emparejamiento_id'=>$gpo->preguntas[0]->grupo_emparejamiento_id
                    ];
                    $pregunta[]=$preg;
                }
                return dataTables()
                    ->of($pregunta)
                    ->addColumn('actions','pregunta/actions')
                    ->rawColumns(['actions'])
                    ->toJson();
            }
        }

        $grupos = Grupo_Emparejamiento::where("area_id",$id_area)->get();

        return view('pregunta.index', compact('area', 'grupos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id_area)
    {
        if(!Area::where('id',$id_area)->first()){
            return redirect('/');
        }
        $area=Area::find($id_area);
        return view('pregunta.create',compact('area'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rule_pregunta='required';
        $rule_gpo='required|exists:grupo_emparejamiento,id';
        $message_pregunta='El campo pregunta es requerido.';
        $message_gpo='Seleccione un grupo de emparejamiento valido.';

        $rules = ['pregunta' => $rule_pregunta];
        $messages = ['pregunta.required' => $message_pregunta];
        $validator = Validator::make(['pregunta'=>$request->pregunta], $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $gpo=new Grupo_Emparejamiento();
        $gpo->area_id=$request->pregunta_id;//En este caso el pregunta_id trae el valor del area
        $gpo->descripcion_grupo_emp="";
        $gpo->save();


        $pregunta=new Pregunta();
        $pregunta->grupo_emparejamiento_id=$gpo->id;
        $pregunta->pregunta=$request->pregunta;
        $pregunta->save();

        $area=Area::find($gpo->area_id);
        if((int)$area->tipo_item->id==2){
            $opcion= new Opcion();
            $opcion->pregunta_id=$pregunta->id;
            $opcion->opcion="Verdadero";
            $opcion->correcta=true;
            $opcion->save();

            $opcion= new Opcion();
            $opcion->pregunta_id=$pregunta->id;
            $opcion->opcion="Falso";
            $opcion->correcta=false;
            $opcion->save();

        }
        return response()->json(['success'=>'Se ha creado la pregunta exitosamente.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_gpo,$id_preg)
    {
        $pregunta=Pregunta::find($id_preg);
        return response()->json($pregunta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id_gpo,$id_preg)
    {
        if(!($gpo=Grupo_Emparejamiento::where('id',$id_gpo)->first())){
            return redirect('/');
        }
        $area=$gpo->area;
        $pregunta=Pregunta::where('id',$id_preg)->first();
        return view('pregunta.update',compact('area','pregunta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id_gpo, Request $request)
    {
        $rule_pregunta='required';
        $rule_gpo='required|exists:grupo_emparejamiento,id';
        $message_pregunta='El campo pregunta es requerido.';
        $message_gpo='Seleccione un grupo de emparejamiento valido.';
        $message_mod='No modifique la pagina web, por favor.';

        $rules = ['pregunta' => $rule_pregunta];
        $messages = ['pregunta_id.exists'=> $message_mod, 'pregunta.required' => $message_pregunta];
        $validator = Validator::make(['pregunta'=>$request->pregunta], $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pregunta=Pregunta::where('id',(int)$request->pregunta_id)->first();
        $pregunta->pregunta=$request->pregunta;
        $pregunta->save();

        $gpo=Grupo_Emparejamiento::where('id',$id_gpo)->first();
        $area=$gpo->area;
        return response()->json(['success'=>'La pregunta se modifico exitosamente.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $pregunta=Pregunta::find((int)$request->id);
        if($pregunta->clave_area_preg_est()->count()!=0){
            $message=['error'=>'La pregunta no puede ser eliminada porque esta siendo utilizada en una evaluacion.','type'=>1];
        }else{
            $message=['success'=>'La pregunta fue eliminada exitosamente.','type'=>2];
            $gpo=$pregunta->grupo_emp;
            $gpo->delete();
        }
        return response()->json($message);
    }

    /**
     * Funcion encargada de retornar la plantilla Excel para posteiormente importar preguntas, usando la plantilla, segun modalidad del area.
     * @return type
     * @author Ricardo Estupinian
     */
    public function downloadExcel($modalidad_area,$id_area){

        //Se busca el area para concatenarla al nombre del area al documento
        $area=Area::find($id_area);

        //Condicional que controla que plantilla enviar segun modalidad del area
        $nombre_descarga="";
        $ruta='plantillaExcel/';

        //Segun la modalidad del area asignara un nombre de descarga ya que 3 de las modalidades
        //comparten el mismo formato de descarga.
        //A diferencia de la modalidad de emparejamiento es otra plantilla
        switch ($modalidad_area) {
            case 1:
                $ruta.='ImportarPreguntasOpcionMultiple.xlsx';
                $nombre_descarga=str_replace(" ","_",$area->titulo)."_SIGEN_Opcion_Multiple.xlsx";
                break;
            case 2:
                 $ruta.='ImportarPreguntasVerdaderoFalso.xlsx';
                $nombre_descarga=str_replace(" ","_",$area->titulo)."_SIGEN_Falso_Verdadero.xlsx";
                break;
            case 3:
                $ruta.='ImportarPreguntasGpo.xlsm';
                $nombre_descarga=str_replace(" ","_",$area->titulo)."_SIGEN_Emparejamiento.xlsm";
                break;
            case 4:
                $ruta.='ImportarPreguntasTextoCorto.xlsx';
                $nombre_descarga=str_replace(" ","_",$area->titulo)."_SIGEN_Texto_Corto.xlsx";
                break;
        }
        return Storage::download($ruta,$nombre_descarga);
    }

    /**
     * Funcion que permite que el docente suba el archivo excel con el formato requerido lo valide e importe los grupos de emparejamiento(modalidad emparejamiento), preguntas y opciones.
     * @var int
     * @author Ricardo Estupinian
     */
    public function uploadExcel(Request $request,$id_area){
        //Se recupera el id del user y la hora actual para guardarlo momentaneamente
        //con un nombre diferente y evitar conflictos a la hora de que hayan subidas multiples
        $id_user = auth()->user()->id;
        $area=Area::find($id_area);
        $modalidad_area=$area->tipo_item->id;

        //Se guarda en la ruta storage/app/importExcel de manera temporal y se recupera la ruta
        $ruta=Storage::putFileAs('importExcel',$request->file('archivo'),$id_user.Carbon::now()->format('His')."Excel.xlsx");

        //Mensaje por defecto
        $message=['error'=>'Hubo un error en la importacion. Verifique que sea el formato adecuado.','type'=>1];
        //$message=['success'=>'La importacion de preguntas se efectuo exitosamente.','type'=>2];
        //Se hara la importacion de las preguntas segun la modalidad del area

        $spreadsheet=null;
        $data=null;
        try{
            //Se carga el archivo que subio el archivo para poder acceder a los datos
            $spreadsheet = IOFactory::load(storage_path($path = "app\\".str_replace("/","\\",$ruta)));

            //A la modalidad de emparejamiento se le dara otro tratamiento
            if($modalidad_area!=3){
                //Todas las filas se convierten en un array que puede ser accedido por las letras de las columnas de archivo excel
                $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            }

        }catch(Exception $e){
            return response()->json($message);
        }

        //Condicional segun modalidad del area, se procedera de manera diferente en la importacion
        switch ($modalidad_area) {
            case 1:
            //Modalidad Opcion Multiple
                //Validacion de plantilla
                if($spreadsheet->getActiveSheet()->getCell('I1')=="1OP"){
                    for ($i=5; $i <=count($data) ; $i++) {
                        if($data[$i]["A"]!=null&&$data[$i]["B"]!=null&&$data[$i]["C"]!=null&&$data[$i]["D"]!=null){
                            //Se crea el grupo de emparejamiento
                            $gpo=new Grupo_Emparejamiento();
                            $gpo->area_id=$area->id;
                            $gpo->save();

                            //Se crea la pregunta segun la columna A
                            $preg=new Pregunta();
                            $preg->grupo_emparejamiento_id=$gpo->id;
                            $preg->pregunta=$data[$i]["A"];
                            $preg->save();

                            foreach ($data[$i] as $key => $val) {
                                if($key=="B"){
                                    //Creacion de opcion correcta
                                    $op=new Opcion();
                                    $op->pregunta_id=$preg->id;
                                    $op->opcion=$val;
                                    $op->correcta=1;
                                    $op->save();
                                }else{
                                    if($key!="A"&&$val!=null){
                                        //Creacion de otras opciones
                                        $op=new Opcion();
                                        $op->pregunta_id=$preg->id;
                                        $op->opcion=$val;
                                        $op->correcta=0;
                                        $op->save();
                                    }
                                }
                            }
                        }
                    }
                    $message=['success'=>'La importacion de preguntas se efectuo exitosamente.','type'=>2];
                }
                break;
            case 2:
            //Modalidad Falso/Verdadero
                //Validacion de plantilla
                if($spreadsheet->getActiveSheet()->getCell('F6')=="2FV"){
                    for($i=2;$i<=count($data);$i++){
                    //Se verifica que la columna en la que esta la pregunta no este vacia
                        if($data[$i]["A"]!=""){
                            //Se crea el grupo de emparejamiento
                            $gpo=new Grupo_Emparejamiento();
                            $gpo->area_id=$area->id;
                            $gpo->save();

                            //Se crea la pregunta segun la columna A
                            $preg=new Pregunta();
                            $preg->grupo_emparejamiento_id=$gpo->id;
                            $preg->pregunta=$data[$i]["A"];
                            $preg->save();

                            //Creacion de opciones falso/verdaero
                            $opv=new Opcion();
                            $opv->pregunta_id=$preg->id;
                            $opv->opcion="Verdadero";

                            $opf=new Opcion();
                            $opf->pregunta_id=$preg->id;
                            $opf->opcion="Falso";

                            //Si hay cualquier otra letra,esta vacia o v la correcta sera Verdadero por defecto
                            $opf->correcta=0;
                            $opv->correcta=1;

                            //Caso si es falsa
                            if(strtolower($data[$i]["B"])=="f"){
                               $opf->correcta=1;
                               $opv->correcta=0;
                            }
                            $opv->save();
                            $opf->save();
                        }
                    }
                    $message=['success'=>'La importacion de preguntas se efectuo exitosamente.','type'=>2];
                }
                break;
            case 3:
            //Modalidad grupo emparejamiento
                //Validacion de plantilla
                if($spreadsheet->getSheetByName("BASE")){

                    if($spreadsheet->getSheetByName("BASE")->getCell('G1')=="3EMP"){

                        //Contamos cuantas hojas son en total
                        for ($j=0; $j < $spreadsheet->getSheetCount() ; $j++) {
                            $data=$spreadsheet->getSheet($j)->toArray(null, true, true, true);

                            //Creamos el grupo de emparejamiento con el nombre de la hoja
                            if($spreadsheet->getSheet($j)->getTitle()!="BASE"){
                                //Creamos el grupo de emparejamiento
                                 $gpo=new Grupo_Emparejamiento();
                                 $gpo->area_id=$area->id;
                                 $gpo->descripcion_grupo_emp= $spreadsheet->getSheet($j)->getTitle();
                                 $gpo->save();

                                for ($i=5; $i <=count($data) ; $i++) {
                                    if($data[$i]["A"]!=null&&$data[$i]["B"]!=null){

                                        //Se crea la pregunta segun la columna A
                                        $preg=new Pregunta();
                                        $preg->grupo_emparejamiento_id=$gpo->id;
                                        $preg->pregunta=$data[$i]["A"];
                                        $preg->save();

                                        foreach ($data[$i] as $key => $val) {
                                            if($key=="B"){
                                                //Creacion de opcion correcta
                                                $op=new Opcion();
                                                $op->pregunta_id=$preg->id;
                                                $op->opcion=$val;
                                                $op->correcta=1;
                                                $op->save();
                                            }
                                            else{
                                                if($key!="A"&&$val!=null){
                                                    //Creacion de otras opciones
                                                    $op=new Opcion();
                                                    $op->pregunta_id=$preg->id;
                                                    $op->opcion=$val;
                                                    $op->correcta=0;
                                                    $op->save();
                                                }
                                            }

                                        }
                                    }
                                }
                            }
                        }
                    }
                    $message=['success'=>'La importacion de preguntas se efectuo exitosamente.','type'=>2];
                }
                break;
            case 4:
            //Modalidad Texto Corto
                //Validacion de plantilla
                if($spreadsheet->getActiveSheet()->getCell('B4')=="4TC"){
                    //dd($data);
                    //Recuperando las columnas para acceder por columnas
                    $columns=null;
                    foreach ($data[1] as $key => $value) {
                        $columns[]=$key;
                    }

                    //dd($columns);
                    for ($j=1; $j < count($columns)-1; $j++){
                        if($data[6][$columns[$j]]!=null){
                            //Se crea el grupo de emparejamiento
                            $gpo=new Grupo_Emparejamiento();
                            $gpo->area_id=$area->id;
                            $gpo->save();

                            //Se crea la pregunta segun la columna A
                            $preg=new Pregunta();
                            $preg->grupo_emparejamiento_id=$gpo->id;
                            $preg->pregunta=$data[6][$columns[$j]];
                            $preg->save();

                            for ($i=7; $i <= count($data) ; $i++) {
                                if($data[$i][$columns[$j]]!=null){
                                    //Creacion de opciones
                                    $op=new Opcion();
                                    $op->pregunta_id=$preg->id;
                                    $op->opcion=$data[$i][$columns[$j]];
                                    $op->correcta=1;
                                    $op->save();
                                }
                            }
                        }
                    }
                    $message=['success'=>'La importacion de preguntas se efectuo exitosamente.','type'=>2];
                }
                break;
        }

        //Eliminar el archivo subido, solo se utiliza para la importacion y luego de desecha
        Storage::delete($ruta);

        return response()->json($message);
    }
}
