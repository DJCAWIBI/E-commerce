<?php
// _models/database.php

class Database {
    private static $host = 'localhost';
    private static $db_name = 'ok_store_db';
    private static $username = 'root';
    private static $password = '12345678'; // Tu contraseña de MySQL
    private static $conn;

    public static function getConexion() {
        
        self::$conn = null;
        $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$db_name . ';charset=utf8mb4';

        try {
            // Creamos la conexión PDO
            self::$conn = new PDO($dsn, self::$username, self::$password);
            
            // Configuramos PDO para que lance excepciones en caso de error
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Configuramos para que los resultados se devuelvan como arrays asociativos
            self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            // Matamos la aplicación si la conexión falla
            die('Error de conexión a la Base de Datos: ' . $e->getMessage());
        }
        
        return self::$conn;
    }
}