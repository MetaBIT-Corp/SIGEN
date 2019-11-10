//Funcion de JS que valida el archivo ingresado al input. Formato y Tamaño.
    function validarFile(all)
    {
        //EXTENSIONES Y TAMANO PERMITIDO.
        var extensiones_permitidas = [".png", ".bmp", ".jpg", ".jpeg", ".gif"];
        var tamano = 4; // EXPRESADO EN MB.
        var rutayarchivo = all.value;
        if(rutayarchivo.length>=191){
            alert("Nombre de imagen demasiado largo");
            document.getElementById(all.id).value = "";
            return; // Si el nombre es no válida ya no chequeo lo de abajo.
        }
        var ultimo_punto = all.value.lastIndexOf(".");
        var extension = rutayarchivo.slice(ultimo_punto, rutayarchivo.length);
        if(extensiones_permitidas.indexOf(extension) == -1)
        {
            alert("Extensión de archivo no valida");
            document.getElementById(all.id).value = "";
            return; // Si la extension es no válida ya no chequeo lo de abajo.
        }
        if((all.files[0].size / 1048576) > tamano)
        {
            alert("El archivo no puede superar los "+tamano+"MB");
            document.getElementById(all.id).value = "";
            return;
        }

        //Desplegamos imagen
        // Creamos el objeto de la clase FileReader
        let reader = new FileReader();

        // Leemos el archivo subido y se lo pasamos a nuestro fileReader
        reader.readAsDataURL(all.files[0]);

        // Le decimos que cuando este listo ejecute el código interno
          reader.onload = function(){
            let preview = document.getElementById('preview'),
                    image = document.createElement('img');

            image.src = reader.result;

            preview.innerHTML = '';
            preview.append(image);
          };
      }