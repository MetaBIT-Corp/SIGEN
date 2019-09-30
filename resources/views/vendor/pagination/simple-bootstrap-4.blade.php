@if ($paginator->hasPages())
    <h5>hola</h5>
    <ul class="pagination" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link bg-danger text-white">Anterior</span>
            </li>
        @else
            <li class="page-item ">
                <input type="button" id="previous_btn" onclick="capturar_data(0)" class="page-link bg-danger text-white" rel="prev" value="Anterior"/>
            </li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item ">
                <input type="button" id="next_btn" onclick="capturar_data(1)" class="page-link bg-danger text-white btn-md" rel="next" value="Siguiente"/>
            </li>
        @else
            <li class="page-item" aria-disabled="true">
                <input type="button" onclick="capturar_data(2)" class="page-link bg-danger text-white btn-md" value="Terminar"/>

                

            </li>
        @endif
    </ul>
@endif

<a id="finish_btn" type="button" style="display:none;" href="{{route('calificar_evaluacion')}}">Terminar</a>