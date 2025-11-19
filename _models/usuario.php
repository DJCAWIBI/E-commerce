<?php
// _models/usuario.php

class Usuario {

    public static function registrar($dni, $nombres, $apellidos, $email, $password, $telefono, $direccion, $referencia) {
        $conexion = Database::getConexion();
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO Clientes (dni, nombres, apellidos, email, password_hash, telefono, direccion, referencia, fecha_registro)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        try {
            $stmt = $conexion->prepare($sql);
            // Pasamos los valores en un array al 'execute'
            $stmt->execute([$dni, $nombres, $apellidos, $email, $password_hash, $telefono, $direccion, $referencia]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function verificarLogin($email, $password) {
        $conexion = Database::getConexion();
        
        $sql = "SELECT * FROM Clientes WHERE email = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$email]);
        
        // fetch() obtiene una sola fila
        $usuario = $stmt->fetch(); 

        if ($usuario) {
            if (password_verify($password, $usuario['password_hash'])) {
                return $usuario;
            }
        }
        
        return false;
    }

    public static function obtenerPorId($id_cliente) {
        $conexion = Database::getConexion();
        $sql = "SELECT * FROM Clientes WHERE id_cliente = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$id_cliente]);
        return $stmt->fetch();
    }

    public static function actualizarPerfil($id_cliente, $nombres, $apellidos, $telefono, $direccion, $referencia) {
        $conexion = Database::getConexion();
        $sql = "UPDATE Clientes 
                SET nombres = ?, apellidos = ?, telefono = ?, direccion = ?, referencia = ?
                WHERE id_cliente = ?";
        
        try {
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$nombres, $apellidos, $telefono, $direccion, $referencia, $id_cliente]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function actualizarPassword($id_cliente, $nuevo_password) {
        $conexion = Database::getConexion();
        $nuevo_hash = password_hash($nuevo_password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE Clientes SET password_hash = ? WHERE id_cliente = ?";
        
        try {
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$nuevo_hash, $id_cliente]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}