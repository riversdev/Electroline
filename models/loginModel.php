<?php
session_start();

require_once "conexion.php";

class Login
{
    public static function registrarUsuario($nombre, $domicilio, $correo, $telefono, $contrasenia)
    {
        $SQL = "SELECT * FROM usuarios WHERE correo = '$correo';";
        $stmt = Conexion::conectar()->prepare($SQL);
        $stmt->execute();
        if (count($stmt->fetchAll()) == 0) {
            $stmt = null;
            $contrasenia = password_hash($contrasenia, PASSWORD_BCRYPT);
            $SQL = "INSERT INTO usuarios (nombre,domicilio,correo,telefono,contrasenia) VALUES ('$nombre','$domicilio','$correo','$telefono','$contrasenia');";
            $stmt = Conexion::conectar()->prepare($SQL);
            if ($stmt->execute()) {
                echo "success|Usuario registrado!";
            } else {
                echo "error|Imposible registrar usuario!";
            }
        } else {
            echo "error|El mail de usuario ya existe!";
        }
        $stmt = null;
    }
    public static function identificarUsuario($correo, $contrasenia)
    {
        $SQL = "SELECT id, contrasenia FROM usuarios WHERE correo='$correo';";
        $stmt = Conexion::conectar()->prepare($SQL);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        if (count($resultado) > 0 && password_verify($contrasenia, $resultado['contrasenia'])) {
            if ($correo == "admin@admin") {
                $_SESSION['admin_id'] = $resultado['id'];
            } else {
                $_SESSION['user_id'] = $resultado['id'];
            }
            echo "success| ";
        } else {
            echo "error|Verifica tus datos!";
        }
        $stmt = null;
    }
    public static function salir()
    {
        session_start();
        session_unset();
        session_destroy();
    }
}
