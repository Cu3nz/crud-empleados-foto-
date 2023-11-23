<?php

use App\Db\Empleado;

session_start();
require_once __DIR__."/../vendor/autoload.php";

Empleado::generarRegistros(10);
$empleado = Empleado::read(); //* En esta variable se almacena un array con todos los registros que hay en la base de datos


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


    <div class=" container p-12 mx-auto">
    
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <?php 
                if (!$empleado){
                    echo <<<TXT
                    <div>
                        <p>No hay ningun registro en la base de datos</p>
                    </div>
                    TXT;
                } else {
                ?>
                <!--//? Boton de crear un nuevo empleado-->
                <div class="flex flex-row-reverse my-2">
                    <a href="create.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 mb-4 px-4 rounded">
                        <i class="fa-solid fa-plus"></i> Nuevo cliente
                    </a>
                </div>
                <!--//? Boton de crear un nuevo empleado-->
            <div class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 pb-4 bg-white dark:bg-gray-900">
            
                <table class="w-full text-sm text-center rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="p-4">
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nombre
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Apellidos
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Puesto
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Salario
                            </th>
                            <th scope="col" class="px-6 py-3">
                                email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Botones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($empleado as $item) {
                            echo <<<TXT
                        
                        
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="w-4 p-4">
                                <div class="flex items-center">
                                    <input id="checkbox-table-search-1" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                                </div>
                            </td>
                            <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                <img class="w-10 h-10 rounded-full" src="./{$item -> imagen}" alt="Jese image">
                                <div class="ps-3">
                                    <div class="text-base font-semibold">{$item -> nombre}</div>
                                    <div class="font-normal text-gray-500">{$item -> email}</div>
                                </div>
                            </th>
                            <td class="px-6 py-4">
                                {$item -> apellidos}
                            </td>
                            <td class="px-6 py-4">
                            {$item -> puesto}
                            </td>
                            <td class="px-6 py-4">
                                {$item -> salario}€
                            </td>
                            <td class="px-6 py-4">
                                {$item -> email}
                            </td>
                            <td class="px-6 py-4">
                                <form action="delete.php" method="post">
                                <a href="update.php?id={$item -> id}"></a>
                                <a title="Pulsa para ir a detalles del empleado" href="detalle.php?id={$item -> id}"><i class="fas fa-info mr-2 text-blue-600"></i></a>
                                <input type="hidden" value="{$item -> id}" name="id">
                                <button type="submit"><i class="fas fa-trash text-red-600"></i></button>
                                </form>
                            </td>
                        </tr>
                        TXT;
                        }
                        ?>
                    </tbody>
                </table>
                <?php 
                }
                ?>
            </div>

        </div>

</body>

</html>