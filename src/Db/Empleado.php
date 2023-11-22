<?php

namespace App\Db;

use App\Utils\Utilidades;
use PDO;

class Empleado extends Conexion
{


    private int $id;
    private string $nombre;
    private string $apellidos;
    private string $puesto;
    private float $salario = 0.0;
    private string $email;
    private string $imagen;

    public function __construct()
    {
        parent::__construct();
    }


    //? ----------------------------- CRUD ------------------

    //todo Metodo que inserta los registros a la tabla empleados2, no es estatico porque utilizamos el $this y tampoco hace falta definir la conexion la conexion.

    public function create(){
        $q = "INSERT INTO empleados2 (nombre, apellidos , puesto , salario , email , imagen) values (:n , :a , :p , :s , :e , :im)";

        $stmt = parent::$conexion -> prepare($q);

        try {
            $stmt -> execute([
                ':n' => $this -> nombre, 
                ':a' => $this -> apellidos, 
                ':p' => $this -> puesto, 
                ':s' => $this -> salario, 
                ':e' => $this -> email, 
                ':im' => $this -> imagen
            ]);
        } catch (\PDOException $ex) {
            die("Error en el metodo create mensaje " . $ex -> getMessage());
        }
    }

    //todo Meotodo read, basicamente lo que hace es devovler en un array todos los objetos o registros que encuentre en la base de datos.

    public static function read(){
        parent::setConexion();

        $q = "SELECT * from empleados2 order by id desc";

        $stmt = parent::$conexion -> prepare($q);

        try {
            $stmt -> execute();
        } catch (\PDOException $ex) {
            die("Error en el metodo read, no se ha podido leer los registros " . $ex -> getMessage());
        }

        parent::$conexion = null;

        return $stmt -> fetchAll(PDO::FETCH_OBJ);
    }


    public static function detalle($idEmpleadoGet){

        parent::setConexion();

        $q = "SELECT * from empleados2 where id = :i";

        $stmt = parent::$conexion -> prepare($q);

        try {
            $stmt -> execute([':i' => $idEmpleadoGet]);
        } catch (\PDOException $ex) {
            die("Error en el metodo FindEmpleado mensaje " . $ex -> getMessage());
        }
        return $stmt -> fetch(PDO::FETCH_OBJ); //* Sabemos que solo va a devolver una fila o registro por lo tanto no hace falta poner fechtAll es tonteria
        parent::$conexion = null;


    }


    //? ----------------------------- FAKER ------------------


    //todo Metodo que comprueba si hay registros creados en la base datos, aunque sea 1.

    public static function hayRegistros(){
        parent::setConexion();

        $q = "SELECT * from empleados2";

        $stmt = parent::$conexion -> prepare($q);

        try {
            $stmt -> execute();
        } catch (\PDOException $ex) {
            die("Error en el metodo hayRegistros mensaje" . $ex -> getMessage());
        }

        return $stmt -> rowCount(); //* Devuelve el numero de filas.
        parent::$conexion = null;

    }


    //todo Metodo que genera datos aleatorios, segun una cantidad pasada por parametro.

    public static function  generarRegistros($cantidadAGenerar){

        if (self::hayRegistros()) return; //* Si hay registros te sales de este metodo.

        parent::setConexion();

        $faker = \Faker\Factory::create("es_ES");
        $faker->addProvider(new \Mmo\Faker\PicsumProvider($faker));
        for ($i=0; $i <$cantidadAGenerar ; $i++) { 
            

            $nombre = $faker -> lastName();
            $apellidos = $faker -> firstName() . " " . $faker -> firstName();
            $puesto = $faker -> randomElement(Utilidades::$puestos); //* Escoge un puesto aleatorio del aray creada en utilidades
            $salario = $faker->randomFloat(2, 10, 100);  //* Precio con dos decimales.
            $email = $faker -> email();
            $imagen = "img/".$faker -> picsum(dir: "./img", width: 640, height: 480, fullPath: false);

            (new Empleado) 
            -> setNombre($nombre)
            -> setApellidos($apellidos)
            -> setPuesto($puesto)
            -> setSalario($salario)
            -> setEmail($email)
            ->setImagen($imagen)
            -> create();
        }

    }


    //? ----------------------------- OTROS MEODOS ------------------

    //todo metodo que comprueba si existe ya el email que introduce el usuario por el input a la hora de crear o de actualizar al empleado


    public static function existeEmailRepetido($email , $id = null ){

        parent::setConexion();

         //*1º Consulta
        //? Determinar la consulta SQL a ejecutar basada en la presencia o ausencia del ID.
        //? Si el ID es nulo, se trata de una operación de CREACIÓN en el CRUD.
        //? En este caso, la consulta verifica si el email ya existe en la base de datos.
        //? Si la consulta devuelve un registro o fila, significa que el email ya está almacenado.
        //? Por lo tanto no se va a poder crear el empleado con un email duplicado
        
        //*2º Consulta
        //? Por otro lado, si el ID no es nulo, se trata de una operación de ACTUALIZACIÓN.
        //? En este caso, la consulta verifica si el email ya está siendo usado por otro empleado o registro basicamente busca si ese email esta ya utilizado por otro empleado que no sea el que se esta actualizando en ese momento,
        //? quitado  el registro actual que estamos actualizado (identificado por el id).
        //? Esto es crucial para permitir la actualización del registro actual sin conflictos de email,
        //? siempre y cuando el email no este siendo usado por otro cliente.

        $q = ($id == null) ? "SELECT email from empleados2 where email = :e" : "SELECT email from empleados2 where email = :e AND id != :i";

        $stmt  = parent::$conexion -> prepare($q);

        $options = ($id == null) ? [':e' => $email] : [':e' => $email , ':i' => $id];

        try {
            $stmt -> execute($options);
        } catch (\PDOException $ex) {
            die("Error en el metodo existeemailRepetido mensaje " . $ex -> getMessage());
        }

        parent::$conexion = null;
        return $stmt -> rowCount();
    }


    //? ----------------------------- SETTERS ------------------
    



    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of apellidos
     */
    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Set the value of puesto
     */
    public function setPuesto(string $puesto): self
    {
        $this->puesto = $puesto;

        return $this;
    }

    /**
     * Set the value of salario
     */
    public function setSalario(float $salario): self
    {
        $this->salario = $salario;

        return $this;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the value of imagen
     */
    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }
}
