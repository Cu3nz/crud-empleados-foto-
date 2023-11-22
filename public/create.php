<?php

use App\Db\Empleado;
use App\Utils\Utilidades;

use const App\Utils\MAY_ON;

require_once __DIR__. "/../vendor/autoload.php";
session_start();

if (isset($_POST['btn'])){

    $nombre =  Utilidades::sanearCadenas($_POST['nombre'],MAY_ON); 
    $apellidos = Utilidades::sanearCadenas($_POST['apellidos'],MAY_ON);
    $puesto = Utilidades::sanearCadenas($_POST['puesto'], MAY_ON);
    $salario = (float) $_POST['salario'];
    $email = Utilidades::sanearCadenas($_POST['email']); //* no le paso nada por lo tanto por defecto me coge la mayuscula off

    echo "$nombre";
    echo "$apellidos";
    echo "$puesto";
    echo "$salario";
    echo "$email";
    

    //*Validaciones 

    $errores = false;

    if (Utilidades::errorCampoTexto('nombre' , $nombre , 5)){
        $errores = true;
    }

    if (Utilidades::errorCampoTexto('apellidos' , $apellidos , 5)){
        $errores = true;
    }

    if (Utilidades::errorCampoTexto('puesto' , $puesto , 5)){
        $errores = true;
    }

    if (Utilidades::errorCampoNumerico('salario' , $salario , 1 , 2000)){
        $errores = true;
       }

    if (Utilidades::validarEmail('email' , $email)){ //? Si no pasa el filtro el email, tenemos un error.
        $errores = true;
    }

    if (Utilidades::emailRepetido('email' , $email)){
        $errores =  true;
    }



    //todo Validacion de la imagen 

    if (is_uploaded_file($_FILES['imagen']['tmp_name'])){ //? validamos si se ha subido una imagen a la carpeta temporal de xammp

        //* Si se ha subido ahora tenemos que validar que la extension de la imagen este en el array definida en utilidades y que no supere un tamaño de 2MB. 
        if (Utilidades::errorTipoImagen($_FILES['imagen']['type'])){ //? Si supera
            $errores = true;
        } else if (Utilidades::errorTamanoImagen($_FILES['imagen']['size'])){
            $errores = true;

        } else { //* Si han pasado las validaciones

            $ruta = "./img/" . uniqid() . "_" . [$_FILES['imagen']['name']];
            
            //* Ahora hacemos el move 
            move_uploaded_file($_FILES['imagen']['tmp_name'] , $ruta);
            $imagen = substr($ruta , 2);
            
        }

    }else {
        $imagen = "./img/default2.png";
    }



    //todo Primero comprobamos si se ha subido una imagen 


    if ($errores){
        header("Location:{$_SERVER['PHP_SELF']}");
        die();
    }


    //* Si estamos aqui es porque hemos pasado las validaciones y podemos crear el empleado.

    (new Empleado) 
    -> setNombre($nombre)
    -> setApellidos($apellidos)
    -> setPuesto($puesto)
    -> setSalario($salario)
    -> setEmail($email)
    ->setImagen($imagen)
    -> create();
    /* $_SESSION['mensaje'] = "Empleado creado de forma exitosa"; */
    header("Location:index.php");
} else {



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fontawesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Crear</title>
</head>

<body style="background-color: burlywood;">
    <div class="container p-12 mx-auto">
        <h1 class="flex justify-center text-xl font-bold text-white m-3">Crear artículo</h1>
        <div class="w-3/4 mx-auto p-6 rounded-xl bg-gray-400">
            <!-- Si vamos a subir archivos hay que poner el enctype="multipart/form-data" en el formulario. -->
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
                <div class="mb-6">
                    <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Nombre</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombre..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <?php 
                        Utilidades::pintarErrores('nombre');
                    ?>
                </div>
                <div class="mb-6">
                    <label for="desc" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Apellidos</label>
                    <input name="apellidos" rows='5' id="desc" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></input>
                    <?php 
                       Utilidades::pintarErrores('apellidos');
                    ?>
                </div>
                <div class="mb-6">
                    <label for="stock" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Puesto</label>
                    <input type="text" id="stock" name="puesto" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" step="1" min="0" />
                    <?php 
                       Utilidades::pintarErrores('puesto');
                    ?>
                </div>
                <div class="mb-6">
                            <label for="precioArticulo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Salario</label>
                            <!--//! gracias a step puedo meter numero decimales y con min para que pueda introducir como minimo 0-->
                            <input id="precioArticulo" type="text" id="stockArticulo" step="0.01" name="salario" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Salario del elmpleado..." >
                            <!--//todo Pintamos los errores-->
                            
                            <?php 
                            Utilidades::pintarErrores('salario');
                            ?>
                        </div>
                <div class="mb-6">
                    <label for="precio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Email</label>
                    <input type="email" id="precio" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" step="0.01" max="9999,99" min="0">
                    <?php 
                       Utilidades::pintarErrores('email');
                    ?>
                </div>
                <div class="mb-6">
                    <div class="flex w-full">
                        <div class="w-1/2 mr-2">
                            <label for="imagen" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                IMAGEN</label>
                            <input type="file"  id="imagen" oninput="img.src=window.URL.createObjectURL(this.files[0])" name="imagen" accept="image/*" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                            <?php 
                               
                            ?>
                        </div>
                        <div class="w-1/2">
                            <img src="./img/default2.png" class="h-72 rounded w-full object-cover border-4 border-black" id="img"> <!--//? Imagen por defecto si no subimos nada-->
                        </div>
                    </div>
                </div>
                <div class="flex flex-row-reverse">
                    <button type="submit" name="btn" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <i class="fas fa-save mr-2"></i>Crear
                    </button>
                    <button type="reset" class="mr-2 text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-blue-800">
                        <i class="fas fa-paintbrush mr-2"></i>Limpiar campos
                    </button>
                    <a href="index.php" class="mr-2 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                        <i class="fas fa-xmark mr-2"></i>Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
<?php 
}
?>