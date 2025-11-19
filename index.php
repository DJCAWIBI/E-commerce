<?php
// index.php

session_start();

// 1. Cargar modelos
require_once '_models/database.php';
require_once '_models/categoria.php';
require_once '_models/producto.php';
require_once '_models/usuario.php';
require_once '_models/pedido.php';

// 2. Cargar controladores
require_once '_controllers/productos_controller.php';
require_once '_controllers/auth_controller.php';
require_once '_controllers/checkout_controller.php';

// 3. Obtener la acción y la categoría
$action = isset($_GET['action']) ? $_GET['action'] : 'default';
$categoria_id = isset($_GET['cat']) ? $_GET['cat'] : 'all';

// 4. Enrutador (Router)
switch ($action) {
    // ---- RUTAS DE AUTENTICACIÓN ----
    case 'login':
        AuthController::mostrarLogin();
        break;
    case 'do-login':
        AuthController::procesarLogin();
        break;
    case 'registro':
        AuthController::mostrarRegistro();
        break;
    case 'do-registro':
        AuthController::procesarRegistro();
        break;
    case 'logout':
        AuthController::cerrarSesion();
        break;
    
    // ---- NUEVO: RUTAS DE PERFIL ----
    case 'mi-cuenta':
        AuthController::mostrarMiCuenta();
        break;
    case 'actualizar-perfil':
        AuthController::actualizarPerfil();
        break;
    case 'actualizar-password':
        AuthController::actualizarPassword();
        break;
    
    // ---- RUTA DE BÚSQUEDA ----
    case 'search':
        $query = isset($_GET['query']) ? $_GET['query'] : '';
        $data = ProductosController::buscar($query);
        require_once '_views/layout.php';
        break;

    // ---- RUTA DE CHECKOUT ----
    case 'procesar-compra':
        CheckoutController::procesar();
        break;

    // ---- RUTA POR DEFECTO (Mostrar productos) ----
    default:
        $data = ProductosController::listar($categoria_id);
        require_once '_views/layout.php';
        break;
}