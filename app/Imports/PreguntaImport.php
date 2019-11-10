<?php

namespace App\Imports;

use App\Pregunta;
use Maatwebsite\Excel\Concerns\ToModel;

class PreguntaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Pregunta([
            //
        ]);
    }
}
