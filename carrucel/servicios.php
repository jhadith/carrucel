<?php


 class servicios{
     public static function conectar(){
    /*servidor
    usuario
    contrasenia
    base de datos*/
    $servidor = "localhost";
    $usuario = "root";
    $clave = "";
    $bd = "bdgal";
    $conexion = mysqli_connect($servidor, $usuario, $clave, $bd);
    return $conexion;

    } 
      public static function insertar($cedula, $total){
        $conexion = servicios::conectar();
        $cedula = mysqli_real_escape_string($conexion, $cedula);
        $total = mysqli_real_escape_string($conexion, $total);
        $query = "INSERT INTO compra (cedula, total) VALUES ('$cedula', '$total')";
        $unir = mysqli_query($conexion, $query);
        return $unir ? mysqli_insert_id($conexion) : false;
    }
 }
