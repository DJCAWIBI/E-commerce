<?php
// admin.php
// (Router exclusivo para el Admin)

session_start();

// 1. Cargar Modelos
require_once '_models/database.php';
require_once '_models/categoria.php';
require_once '_models/producto.php';
require_once '_models/pedido.php';

// 2. Cargar Modelos y Controladores del Admin
require_once '_models/admin_model.php';
require_once '_controllers/admin_controller.php';

// 3. Definir una URL base
define('ADMIN_URL', 'admin.php');

// 4. Obtener la acción
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

// 5. Enrutador del Admin
switch ($action) {
    // --- RUTAS DE AUTENTICACIÓN ---
    case 'login':
        AdminController::mostrarLogin();
        break;
    case 'do-login':
        AdminController::procesarLogin();
        break;
    case 'logout':
        AdminController::cerrarSesion();
        break;

    // --- RUTAS DE PRODUCTOS ---
    case 'productos':
        AdminController::listarProductos($action);
        break;
    case 'producto_nuevo':
        AdminController::mostrarFormularioProducto($action);
        break;
    case 'producto_editar':
        AdminController::mostrarFormularioProducto($action);
        break;
    case 'producto_guardar':
        AdminController::procesarGuardarProducto();
        break;
    case 'producto_eliminar':
        AdminController::eliminarProducto();
        break;
    
    // --- RUTAS DE CATEGORÍAS ---
    case 'categorias':
        AdminController::listarCategorias($action);
        break;
    case 'categoria_nueva':
        AdminController::mostrarFormularioCategoria($action);
        break;
    case 'categoria_editar':
        AdminController::mostrarFormularioCategoria($action);
        break;
    case 'categoria_guardar':
        AdminController::procesarGuardarCategoria();
        break;
    case 'categoria_eliminar':
        AdminController::eliminarCategoria();
        break;

    // --- RUTAS DE PEDIDOS ---
    case 'pedidos':
        AdminController::listarPedidos($action);
        break;
    
    // Ruta AJAX para el modal de detalles
    case 'pedido_detalle_ajax':
        AdminController::obtenerDetallePedidoAjax();
        break;

    // --- RUTA DEL DASHBOARD (POR DEFECTO) ---
    case 'dashboard':
    default:
        $action = 'dashboard';
        AdminController::mostrarDashboard($action); 
        break;
}