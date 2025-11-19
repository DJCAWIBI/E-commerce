<?php
// _models/producto.php

class Producto {

    public static function obtenerTodos() {
        $conexion = Database::getConexion();
        $sql = "SELECT p.*, c.nombre_cat as nombre_categoria 
                FROM Productos p
                LEFT JOIN Categorias c ON p.id_categoria = c.id_categoria
                ORDER BY p.nombre_prod";
        $stmt = $conexion->query($sql);
        return $stmt->fetchAll();
    }

    public static function obtenerPorCategoriaPadre($id_padre) {
        $conexion = Database::getConexion();
        $sql = "SELECT p.* FROM Productos p
                JOIN Categorias c ON p.id_categoria = c.id_categoria
                WHERE c.id_categoria_padre = ? AND p.activo = 1";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$id_padre]);
        return $stmt->fetchAll();
    }

    public static function buscarPorNombre($query) {
        $conexion = Database::getConexion();
        $termino_busqueda = "%" . $query . "%";
        $sql = "SELECT * FROM Productos WHERE nombre_prod LIKE ? AND activo = 1";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$termino_busqueda]);
        return $stmt->fetchAll();
    }

    // --- FUNCIONES DEL CRUD DE ADMIN ---

    public static function obtenerPorId($id_producto) {
        $conexion = Database::getConexion();
        $sql = "SELECT * FROM Productos WHERE id_producto = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$id_producto]);
        return $stmt->fetch();
    }

    public static function crearProducto($id_categoria, $nombre_prod, $descripcion_prod, $precio, $stock, $sku, $imagen_url) {
        $conexion = Database::getConexion();
        $sql = "INSERT INTO Productos (id_categoria, nombre_prod, descripcion_prod, precio, stock, sku, imagen_url, activo)
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
        try {
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$id_categoria, $nombre_prod, $descripcion_prod, $precio, $stock, $sku, $imagen_url]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function actualizarProducto($id_producto, $id_categoria, $nombre_prod, $descripcion_prod, $precio, $stock, $sku, $imagen_url) {
        $conexion = Database::getConexion();
        $sql = "UPDATE Productos 
                SET id_categoria = ?, nombre_prod = ?, descripcion_prod = ?, precio = ?, stock = ?, sku = ?, imagen_url = ?
                WHERE id_producto = ?";
        try {
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$id_categoria, $nombre_prod, $descripcion_prod, $precio, $stock, $sku, $imagen_url, $id_producto]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function eliminarProducto($id_producto) {
        $conexion = Database::getConexion();
        
        $sql_check = "SELECT COUNT(*) as total FROM Detalle_Pedidos WHERE id_producto = ?";
        $stmt_check = $conexion->prepare($sql_check);
        $stmt_check->execute([$id_producto]);
        $resultado = $stmt_check->fetch();

        if ($resultado['total'] > 0) {
            return false;
        }

        $sql_delete = "DELETE FROM Productos WHERE id_producto = ?";
        $stmt_delete = $conexion->prepare($sql_delete);
        return $stmt_delete->execute([$id_producto]);
    }
}