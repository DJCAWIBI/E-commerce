<?php
// _controllers/admin_controller.php

class AdminController {

    private static function checkAuth() {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . ADMIN_URL . '?action=login');
            exit;
        }
    }

    // --- AUTENTICACIÓN ---
    public static function mostrarLogin() {
        require_once '_views/admin/login.php';
    }
    public static function procesarLogin() {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $admin = AdminModel::verificarLogin($email, $password);
        if ($admin) {
            $_SESSION['admin_id'] = $admin['id_admin'];
            $_SESSION['admin_nombre'] = $admin['nombre'];
            header('Location: ' . ADMIN_URL . '?action=dashboard');
        } else {
            $error = "Correo o contraseña incorrectos.";
            require_once '_views/admin/login.php';
        }
    }
    public static function cerrarSesion() {
        session_destroy();
        header('Location: ' . ADMIN_URL . '?action=login');
    }

    // --- DASHBOARD ---
    public static function mostrarDashboard($action) { 
        self::checkAuth();
        $data = [
            'titulo_pagina' => 'Dashboard',
            'nombre_admin' => $_SESSION['admin_nombre'],
            'action' => $action
        ];
        require_once '_views/admin/layout.php';
    }

    // --- CRUD PRODUCTOS ---
    public static function listarProductos($action) {
        self::checkAuth();
        $productos = Producto::obtenerTodos();
        $data = [
            'titulo_pagina' => 'Gestionar Productos',
            'nombre_admin' => $_SESSION['admin_nombre'],
            'action' => $action,
            'productos' => $productos
        ];
        require_once '_views/admin/layout.php';
    }
    public static function mostrarFormularioProducto($action) {
        self::checkAuth();
        $producto = null;
        $titulo_formulario = 'Crear Nuevo Producto';
        if ($action == 'producto_editar' && isset($_GET['id'])) {
            $producto = Producto::obtenerPorId($_GET['id']);
            $titulo_formulario = 'Editar Producto';
        }
        $subcategorias = Categoria::obtenerTodasSubcategorias();
        $data = [
            'titulo_pagina' => $titulo_formulario,
            'nombre_admin' => $_SESSION['admin_nombre'],
            'action' => $action,
            'producto' => $producto,
            'subcategorias' => $subcategorias
        ];
        require_once '_views/admin/layout.php';
    }
    public static function procesarGuardarProducto() {
        self::checkAuth();
        $id_producto = isset($_POST['id_producto']) ? $_POST['id_producto'] : null;
        $id_categoria = $_POST['id_categoria'];
        $nombre_prod = $_POST['nombre_prod'];
        $descripcion_prod = $_POST['descripcion_prod'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];
        $sku = $_POST['sku'];
        $imagen_url = $_POST['imagen_url'];
        if (empty($id_producto)) {
            Producto::crearProducto($id_categoria, $nombre_prod, $descripcion_prod, $precio, $stock, $sku, $imagen_url);
        } else {
            Producto::actualizarProducto($id_producto, $id_categoria, $nombre_prod, $descripcion_prod, $precio, $stock, $sku, $imagen_url);
        }
        header('Location: ' . ADMIN_URL . '?action=productos');
    }
    public static function eliminarProducto() {
        self::checkAuth();
        $id_producto = $_GET['id'];
        $exito = Producto::eliminarProducto($id_producto);
        if ($exito) {
            header('Location: ' . ADMIN_URL . '?action=productos');
        } else {
            header('Location: ' . ADMIN_URL . '?action=productos&status=delete_error');
        }
    }

    // --- CRUD CATEGORÍAS ---
    public static function listarCategorias($action) {
        self::checkAuth();
        $categorias = Categoria::obtenerTodasCompletas();
        $data = [
            'titulo_pagina' => 'Gestionar Categorías',
            'nombre_admin' => $_SESSION['admin_nombre'],
            'action' => $action,
            'categorias' => $categorias
        ];
        require_once '_views/admin/layout.php';
    }
    public static function mostrarFormularioCategoria($action) {
        self::checkAuth();
        $categoria = null;
        $titulo_formulario = 'Crear Nueva Categoría';
        if ($action == 'categoria_editar' && isset($_GET['id'])) {
            $categoria = Categoria::obtenerPorId($_GET['id']);
            $titulo_formulario = 'Editar Categoría';
        }
        $categorias_padre = Categoria::obtenerCategoriasPadre();
        $data = [
            'titulo_pagina' => $titulo_formulario,
            'nombre_admin' => $_SESSION['admin_nombre'],
            'action' => $action,
            'categoria' => $categoria,
            'categorias_padre' => $categorias_padre
        ];
        require_once '_views/admin/layout.php';
    }
    public static function procesarGuardarCategoria() {
        self::checkAuth();
        $id_categoria = isset($_POST['id_categoria']) ? $_POST['id_categoria'] : null;
        $nombre_cat = $_POST['nombre_cat'];
        $descripcion_cat = $_POST['descripcion_cat'];
        $id_categoria_padre = $_POST['id_categoria_padre'];
        if (empty($id_categoria)) {
            Categoria::crearCategoria($nombre_cat, $descripcion_cat, $id_categoria_padre);
        } else {
            Categoria::actualizarCategoria($id_categoria, $nombre_cat, $descripcion_cat, $id_categoria_padre);
        }
        header('Location: ' . ADMIN_URL . '?action=categorias');
    }
    public static function eliminarCategoria() {
        self::checkAuth();
        $id_categoria = $_GET['id'];
        $resultado = Categoria::eliminarCategoria($id_categoria);
        if ($resultado == 'true') {
            header('Location: ' . ADMIN_URL . '?action=categorias');
        } else {
            header('Location: ' . ADMIN_URL . '?action=categorias&status=' . $resultado);
        }
    }

    // --- FUNCIONES DE PEDIDOS ---

    public static function listarPedidos($action) {
        self::checkAuth();
        
        $pedidos = Pedido::obtenerTodosLosPedidos();
        
        $data = [
            'titulo_pagina' => 'Gestionar Pedidos',
            'nombre_admin' => $_SESSION['admin_nombre'],
            'action' => $action,
            'pedidos' => $pedidos
        ];
        
        require_once '_views/admin/layout.php';
    }

    /**
     * ¡NUEVA FUNCIÓN!
     * Responde a la llamada AJAX y devuelve los detalles del pedido en JSON
     */
    public static function obtenerDetallePedidoAjax() {
        self::checkAuth(); // Seguridad
        
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID de pedido no proporcionado']);
            exit;
        }
        
        $id_pedido = $_GET['id'];
        $detalles = Pedido::obtenerDetalleDelPedido($id_pedido);
        
        // Devolvemos los datos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($detalles);
        exit; // Detenemos la ejecución (no queremos cargar HTML)
    }
}