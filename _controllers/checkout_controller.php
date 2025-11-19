<?php
// _controllers/checkout_controller.php

class CheckoutController {

    public static function procesar() {
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['success' => false, 'error' => 'login_required']);
            exit;
        }

        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        
        $carrito = $data['carrito'];
        $paypal_id = $data['paypal_id'];

        if (empty($carrito)) {
            echo json_encode(['success' => false, 'error' => 'cart_empty']);
            exit;
        }

        $cliente_id = $_SESSION['usuario_id'];
        
        // $resultado_pedido ahora puede ser un array de éxito o de error
        $resultado_pedido = Pedido::crearPedido($cliente_id, $carrito, $paypal_id);

        // ¡CAMBIO! Verificamos el array de respuesta
        if (isset($resultado_pedido['success']) && $resultado_pedido['success']) {
            // Éxito
            echo json_encode([
                'success' => true, 
                'message' => '¡Pedido realizado con éxito y guardado!',
                'pedido' => $resultado_pedido['pedido'] 
            ]);
        } else {
            // Hubo un error (stock o db)
            if (isset($resultado_pedido['error']) && $resultado_pedido['error'] == 'no_stock') {
                // Error específico de stock
                echo json_encode([
                    'success' => false, 
                    'error' => 'no_stock', 
                    'message' => '¡Stock insuficiente para: ' . $resultado_pedido['producto'] . '! El pedido ha sido cancelado.'
                ]);
            } else {
                // Otro error de base de datos
                echo json_encode([
                    'success' => false, 
                    'error' => 'db_error', 
                    'message' => 'Error al procesar el pedido en la base de datos.'
                ]);
            }
        }
    }
}