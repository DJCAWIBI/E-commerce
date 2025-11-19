<?php
// _controllers/productos_controller.php

class ProductosController {

    public static function listar($categoria_id) {
        $categorias_menu = Categoria::obtenerCategoriasPadre();

        if ($categoria_id == 'all') {
            $productos = Producto::obtenerTodos();
            $nombre_categoria_actual = "Todos los Productos";
        } else {
            $id_padre = intval($categoria_id);
            $productos = Producto::obtenerPorCategoriaPadre($id_padre);
            $nombre_categoria_actual = Categoria::obtenerNombreCategoria($id_padre);
        }

        $data = [
            "categorias_menu" => $categorias_menu,
            "productos" => $productos,
            "nombre_categoria_actual" => $nombre_categoria_actual,
            "id_categoria_seleccionada" => $categoria_id
        ];

        return $data;
    }

    // --- NUEVO MÉTODO PARA BÚSQUEDA ---
    public static function buscar($query) {
        $categorias_menu = Categoria::obtenerCategoriasPadre();
        $productos = Producto::buscarPorNombre($query);

        $data = [
            "categorias_menu" => $categorias_menu,
            "productos" => $productos,
            "nombre_categoria_actual" => "Resultados para: '" . htmlspecialchars($query) . "'",
            "id_categoria_seleccionada" => 'search' // Para marcar que no hay categoría activa
        ];

        return $data;
    }
}