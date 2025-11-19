<?php
// _models/admin_model.php

class AdminModel {

    public static function verificarLogin($email, $password) {
        $conexion = Database::getConexion();
        
        $sql = "SELECT * FROM Administradores WHERE email = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$email]);
        
        // fetch() obtiene una sola fila
        $admin = $stmt->fetch(); 

        if ($admin) {
            if (password_verify($password, $admin['password_hash'])) {
                return $admin;
            }
        }
        
        return false;
    }
}