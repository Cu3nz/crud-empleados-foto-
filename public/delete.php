<?php

use App\Db\Empleado;

if (!isset($_POST['id'])){
    header("Location:index.php");
    die();
}

require_once __DIR__."/../vendor/autoload.php";

//* Capturamos el id que se pasa por post 

$idEmpleadoPost = $_POST['id'];

//todo nos guardamos el empleado entero, para saber que imagen tiene.

$empleado = Empleado::detalle($idEmpleadoPost);

/* var_dump($empleado);
die(); */

//todo empezamos con la imagen, tenemos que saber que si es distinta a la default la tiene que borrar

if (basename($empleado -> imagen) != "default.png"){ //? Recoge solamente el nombre de la imagen de la base de datos, le quita el /img y todo eso... 
    unlink("./" .$empleado -> imagen); //* Le tenemos que especificar ./ porque se tiene que salir del archivo delete  y meterse en img, pero no hace falta especificarlo, porque la imagen guardada en la base de datos es --> img/dkjfkjdkfdfd.png
}

Empleado::delete($idEmpleadoPost);
header("Location:index.php");

?>