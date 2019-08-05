@extends("../layouts.plantilla")
@section("head")
@endsection

@section("body")
@section("ol_breadcrumb")
<a href="#">
    Evaluacion \ Parcial I
</a>
@endsection
@section("main")
<!--Card que contiene a cada pregunta-->
<div class="card">
    <div class="card-body">
        <!--Aqui iran las preguntas-->
        <div class="card bg-light">
            <div class="card-header">
                PREGUNTA Opcion Multiple
            </div>
            <div class="card-body">
                <div class="custom-control custom-radio">
                    <input class="custom-control-input" id="customRadio1" name="customRadio" type="radio">
                        <label class="custom-control-label" for="customRadio1">
                            Opcion 1
                        </label>
                    </input>
                </div>
                <div class="custom-control custom-radio">
                    <input class="custom-control-input" id="customRadio2" name="customRadio" type="radio">
                        <label class="custom-control-label" for="customRadio2">
                            Opcion 2
                        </label>
                    </input>
                </div>
            </div>
        </div>
        <div class="card bg-light mt-2">
            <div class="card-header">
                PREGUNTA Falso Verdadero
            </div>
            <div class="card-body">
                <div class="custom-control custom-radio">
                    <input class="custom-control-input" id="customRadio1" name="customRadio" type="radio">
                        <label class="custom-control-label" for="customRadio1">
                            Verdadero
                        </label>
                    </input>
                </div>
                <div class="custom-control custom-radio">
                    <input class="custom-control-input" id="customRadio2" name="customRadio" type="radio">
                        <label class="custom-control-label" for="customRadio2">
                            Falso
                        </label>
                    </input>
                </div>
            </div>
        </div>
        <div class="card bg-light mt-2">
            <div class="card-header">
                PREGUNTA Texto Corto
            </div>
            <div class="card-body">
                <input aria-describedby="emailHelp" class="form-control" id="exampleInputEmail1" placeholder="Ingrese respuesta" type="text">
                </input>
            </div>
        </div>
        <div class="card bg-light mt-2">
            <div class="card-header">
                PREGUNTA Emparejamiento
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                Pregunta a responder con los selects
                            </th>
                            <td>
                                <select class="custom-select col-3" id="inputGroupSelect01">
                                    <option selected="">
                                        Choose...
                                    </option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="card-footer text-center">

        <!--Botones de control para paginacion-->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="#">
                        Anterior
                    </a>
                </li>
                <li class="page-item active">
                    <a class="page-link" href="#">
                        1
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">
                        2
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">
                        3
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">
                        Siguiente
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection
@endsection
