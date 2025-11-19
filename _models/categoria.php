<?php
// _models/categoria.php

class Categoria {
    
    public static function obtenerCategoriasPadre() {
        $conexion = Database::getConexion();
        $sql = "SELECT * FROM Categorias WHERE id_categoria_padre IS NULL ORDER BY nombre_cat";
        $stmt = $conexion->query($sql);
        return $stmt->fetchAll(); 
    }

    public static function obtenerNombreCategoria($id) {
        $conexion = Database::getConexion();
        $sql = "SELECT nombre_cat FROM Categorias WHERE id_categoria = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$id]);
        $categoria = $stmt->fetch();
        return $categoria['nombre_cat'];
    }

    public static function obtenerTodasSubcategorias() {
        $conexion = Database::getConexion();
        $sql = "SELECT * FROM Categorias WHERE id_categoria_padre IS NOT NULL ORDER BY nombre_cat";
        $stmt = $conexion->query($sql);
        return $stmt->fetchAll();
    }

    // --- NUEVAS FUNCIONES PARA EL CRUD DE ADMIN ---

    /**
     * Obtiene todas las categorías (hijas y padres)
     * Usamos un LEFT JOIN para saber el nombre del padre
     */
    public static function obtenerTodasCompletas() {
        $conexion = Database::getConexion();
        $sql = "SELECT c.*, p.nombre_cat as nombre_padre 
                FROM Categorias c
                LEFT JOIN Categorias p ON c.id_categoria_padre = p.id_categoria
                ORDER BY p.nombre_cat, c.nombre_cat";
        $stmt = $conexion->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Obtiene una sola categoría por su ID
     */
    public static function obtenerPorId($id_categoria) {
        $conexion = Database::getConexion();
        $sql = "SELECT * FROM Categorias WHERE id_categoria = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$id_categoria]);
        return $stmt->fetch();
    }

    /**
     * Crea una nueva categoría
     */
    public static function crearCategoria($nombre, $descripcion, $id_padre) {
        $conexion = Database::getConexion();
        // Si $id_padre está vacío (string 'null' del form), lo convertimos a NULL
        $id_padre = ($id_padre == 'null' || $id_padre == '') ? null : $id_padre;
        
        $sql = "INSERT INTO Categorias (nombre_cat, descripcion_cat, id_categoria_padre) VALUES (?, ?, ?)";
        try {
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$nombre, $descripcion, $id_padre]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Actualiza una categoría existente
     */
    public static function actualizarCategoria($id_categoria, $nombre, $descripcion, $id_padre) {
        $conexion = Database::getConexion();
        $id_padre = ($id_padre == 'null' || $id_padre == '') ? null : $id_padre;

        $sql = "UPDATE Categorias 
                SET nombre_cat = ?, descripcion_cat = ?, id_categoria_padre = ?
                WHERE id_categoria = ?";
        try {
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$nombre, $descripcion, $id_padre, $id_categoria]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Elimina una categoría
     * Devuelve 'true' si se borra, 'error_productos' si tiene productos, 'error_hijas' si tiene subcategorías
     */
    public static function eliminarCategoria($id_categoria) {
        $conexion = Database::getConexion();
        
        // 1. (Seguridad) Comprobar si tiene productos asociados
        $sql_check_prod = "SELECT COUNT(*) as total FROM Productos WHERE id_categoria = ?";
        $stmt_check_prod = $conexion->prepare($sql_check_prod);
        $stmt_check_prod->execute([$id_categoria]);
        if ($stmt_check_prod->fetch()['total'] > 0) {
            return 'error_productos';
        }

        // 2. (Seguridad) Comprobar si tiene categorías hijas asociadas
        $sql_check_hijas = "SELECT COUNT(*) as total FROM Categorias WHERE id_categoria_padre = ?";
        $stmt_check_hijas = $conexion->prepare($sql_check_hijas);
        $stmt_check_hijas->execute([$id_categoria]);
        if ($stmt_check_hijas->fetch()['total'] > 0) {
            return 'error_hijas';
        }

        // 3. Si no tiene nada, la borramos
        $sql_delete = "DELETE FROM Categorias WHERE id_categoria = ?";
        $stmt_delete = $conexion->prepare($sql_delete);
        return $stmt_delete->execute([$id_categoria]) ? 'true' : 'false';
    }
}