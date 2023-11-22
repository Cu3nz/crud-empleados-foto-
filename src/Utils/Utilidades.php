<?php

namespace App\Utils;

use App\Db\Empleado;

const MAY_ON = 1;

const MAY_OFF = 0;

class Utilidades{


    public static array $puestos = [
        "Desarrollador de Software",
        "Desarrollador Web",
        "Desarrollador de Aplicaciones Móviles",
        "Diseñador UI/UX",
        "Ingeniero de Software",
        "Analista de Sistemas"

    ];


    public static function sanearCadenas($valor , $modo = MAY_OFF){ //* Se le pasa un valor y un modo que va a ser que la primera letra de la cadena no se ponga en mayuscula.
        //todo Si el valor es 1, es porque queremos ponerlo en mayuscula por lo tanto ejecuta la siguiente linea: 
        //? ucfirst(htmlspecialchars(trim($valor)))
        //todo Si no le pasamos nada o le pasamos 0, no ponemos en mayuscula la primera letra de la cadena y ejecutamos la siguiente linea: 
        //? htmlspecialchars(trim($valor))
       return  ($modo == MAY_ON) ? ucfirst(htmlspecialchars(trim($valor))) : htmlspecialchars(trim($valor));
    }

    public static function errorCampoTexto($campo , $valor , $logitud){

        if (strlen($valor) < $logitud){
            $_SESSION[$campo] = "****ERROR: El campo $campo tiene que tener como minimo $logitud caracteres";
            return true; //! hay error
        }
        return false; //* No hay error
    }
    
    public static function errorCampoNumerico ($campo , $valor , $min , $max){
        
        if ($valor < $min || $valor > $max){
            $_SESSION[$campo] = "****ERROR: Tienes que introducir un salario que este entre el rango $min € y $max €";
            return true; //! hay error
        }
        return false; //* No hay error
    }

    public static function validarEmail($campo , $email){
        if (!filter_var($email , FILTER_VALIDATE_EMAIL)){
            $_SESSION[$campo] = "****ERROR: el email introducido no es valido";
            return true; //! hay un error.
        }
        return false;
    }




    public static function emailRepetido($campo , $email){

        if (Empleado::existeEmailRepetido($email)){
            $_SESSION[$campo] = "****Error : ya existe un email igual en la base de datos.";
            return true; //! Hay un error
        }
        return false;
    }


    public static function pintarErrores ($nombreErrorSesion){
        if (isset($_SESSION[$nombreErrorSesion])){
            echo "<p class='text-red-600 italic mt-2 ml-3 '>{$_SESSION[$nombreErrorSesion]}</p>";
            unset($_SESSION[$nombreErrorSesion]);
        }
    }


    static array $tiposMime = [
        'image/gif',
        'image/png',
        'image/jpeg',
        'image/bmp',
        'image/webp' //! Cuidado, el ultimo no tiene que tener la coma
     ];

    public static function errorTipoImagen($tipo){

        if (!in_array($tipo , self::$tiposMime)){//* Con self porque es estatico., compribamos que si el tipo NO esta dentro del array, mostrara un error.
            $_SESSION['imagen'] = "Error, el tipo de archivo introducdo no es una imagen";
            return true; //? error
        } 
        
        return false; //* no hay error.

    }


    public static function errorTamanoImagen($tamanoImagen){

        if ($tamanoImagen > 2000000){
            $_SESSION['Imagen'] = "****Error la imagen que intentas subir supera los 2MB";
            return true;
        }
        return false;
    }

      





}
