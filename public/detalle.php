<?php

use App\Db\Empleado;

if (!isset($_GET['id'])) {
    header("Location:index.php");
    die();
}

require_once __DIR__ . "/../vendor/autoload.php";
$idEmpleadoGet = $_GET['id'];
$empleados = Empleado::detalle($idEmpleadoGet);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- CDN tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- //todo sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>



    <div class="max-w-sm mx-auto mt-60 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <img class="rounded-t-lg" src="<?php echo "./".$empleados -> imagen ?>" alt="" />
        <div class="p-5">
            
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?php echo $empleados -> nombre ?></h5>
            
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"><?php echo $empleados -> apellidos ?></p>
            <span class="font-bold">Puesto:</span> <?php echo $empleados -> puesto ?> <br>
            <span class="font-bold">Salario:</span> <?php echo $empleados -> salario ?>€ <br>
            <span class="font-bold">Email:</span> <?php echo $empleados -> email ?><br>
            <a href="index.php" class="inline-flex items-center  mt-2 px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <i class="fas fa-home mr-2"></i>Ir a home
                
            </a>
        </div>
    </div>


</body>

</html>