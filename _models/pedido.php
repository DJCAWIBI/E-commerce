<?php
// _models/pedido.php

class Pedido {

    /**
     * Obtiene todos los pedidos de un cliente específico
     */
    public static function obtenerPedidosPorCliente($cliente_id) {
        $conexion = Database::getConexion();
        $sql = "SELECT * FROM Pedidos WHERE id_cliente = ? ORDER BY fecha_pedido DESC";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$cliente_id]);
        return $stmt->fetchAll();
    }

    /**
     * Crea un nuevo pedido
     */
    public static function crearPedido($cliente_id, $carrito, $paypal_id = null) {
        $conexion = Database::getConexion();
        $conexion->beginTransaction(); 
        $fecha_actual = date('Y-m-d H:i:s');

        try {
            // --- 1. VERIFICACIÓN DE STOCK ---
            $sql_check_stock = "SELECT stock, nombre_prod FROM Productos WHERE id_producto = ? FOR UPDATE";
            $stmt_check = $conexion->prepare($sql_check_stock);
            
            foreach ($carrito as $item) {
                $stmt_check->execute([$item['id']]);
                $producto_db = $stmt_check->fetch();

                if (!$producto_db || $producto_db['stock'] < $item['cantidad']) {
                    $conexion->rollBack(); // Revertimos
                    return ['error' => 'no_stock', 'producto' => $producto_db['nombre_prod'] ?? 'Producto ID ' . $item['id']];
                }
            }

            // 2. Obtener la dirección del cliente
            $sql_cliente = "SELECT direccion, referencia FROM Clientes WHERE id_cliente = ?";
            $stmt_cliente = $conexion->prepare($sql_cliente);
            $stmt_cliente->execute([$cliente_id]);
            $cliente = $stmt_cliente->fetch();
            $direccion_envio = $cliente['direccion'];
            $referencia_envio = $cliente['referencia'];
            
            // 3. Calcular el total
            $total_pedido = 0;
            foreach ($carrito as $item) {
                $total_pedido += $item['precio'] * $item['cantidad'];
            }

            // 4. Insertar en la tabla 'Pedidos'
            $metodo_pago = $paypal_id ? 'PayPal' : 'Efectivo';
            $estado = 'Pagado';
            $sql_pedido = "INSERT INTO Pedidos (id_cliente, total, direccion_envio, referencia_envio, metodo_pago, id_transaccion_paypal, estado, fecha_pedido)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_pedido = $conexion->prepare($sql_pedido);
            // i (int), d (double), s (string) x 6
            $stmt_pedido->bind_param("idssssss", $cliente_id, $total_pedido, $direccion_envio, $referencia_envio, $metodo_pago, $paypal_id, $estado, $fecha_actual);
            $stmt_pedido->execute();
            $id_pedido_nuevo = $conexion->insert_id;

            // 5. Insertar en 'Detalle_Pedidos' Y RESTAR STOCK
            $sql_detalle = "INSERT INTO Detalle_Pedidos (id_pedido, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
            $stmt_detalle = $conexion->prepare($sql_detalle);
            $sql_update_stock = "UPDATE Productos SET stock = stock - ? WHERE id_producto = ?";
            $stmt_update = $conexion->prepare($sql_update_stock);

            foreach ($carrito as $item) {
                $stmt_detalle->execute([$id_pedido_nuevo, $item['id'], $item['cantidad'], $item['precio']]);
                $stmt_update->execute([$item['cantidad'], $item['id']]);
            }
            
            // 6. Commit
            $conexion->commit(); 
            
            // 7. Devolver éxito
            return [
                'success' => true,
                'pedido' => [
                    'id_pedido' => $id_pedido_nuevo,
                    'total' => $total_pedido,
                    'fecha_pedido' => $fecha_actual,
                    'estado' => $estado
                ]
            ];

        } catch (Exception $e) {
            $conexion->rollBack(); 
            return ['error' => 'db_error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Obtiene todos los pedidos (para el Admin)
     */
    public static function obtenerTodosLosPedidos() {
        $conexion = Database::getConexion();
        $sql = "SELECT p.*, c.nombres, c.apellidos, c.email
                FROM Pedidos p
                JOIN Clientes c ON p.id_cliente = c.id_cliente
                ORDER BY p.fecha_pedido DESC";
        $stmt = $conexion->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * ¡NUEVA FUNCIÓN!
     * Obtiene los productos (detalles) de un solo pedido
     */
    public static function obtenerDetalleDelPedido($id_pedido) {
        $conexion = Database::getConexion();
        
        $sql = "SELECT dp.*, p.nombre_prod, p.sku 
                FROM Detalle_Pedidos dp
                JOIN Productos p ON dp.id_producto = p.id_producto
                WHERE dp.id_pedido = ?";
                
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$id_pedido]);
        return $stmt->fetchAll();
    }
}