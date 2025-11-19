<table class="admin-table">
    <thead>
        <tr>
            <th>ID Pedido</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Email</th>
            <th>Total</th>
            <th>Método</th>
            <th>ID Transacción</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['pedidos'] as $pedido): ?>
        <tr>
            <td><strong>#<?php echo $pedido['id_pedido']; ?></strong></td>
            <td><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></td>
            <td><?php echo htmlspecialchars($pedido['nombres'] . ' ' . $pedido['apellidos']); ?></td>
            <td><?php echo htmlspecialchars($pedido['email']); ?></td>
            <td><strong>S/ <?php echo number_format($pedido['total'], 2); ?></strong></td>
            <td><?php echo htmlspecialchars($pedido['metodo_pago']); ?></td>
            <td><?php echo htmlspecialchars($pedido['id_transaccion_paypal'] ?? 'N/A'); ?></td>
            <td>
                <span style="font-weight: bold; color: <?php echo ($pedido['estado'] == 'Pagado') ? '#218838' : '#b94a48'; ?>;">
                    <?php echo htmlspecialchars($pedido['estado']); ?>
                </span>
            </td>
            <td class="acciones">
                <button type="button" class="btn btn-editar" 
                        onclick="mostrarDetallePedido(
                            <?php echo $pedido['id_pedido']; ?>, 
                            '<?php echo htmlspecialchars($pedido['nombres'] . ' ' . $pedido['apellidos']); ?>',
                            '<?php echo number_format($pedido['total'], 2); ?>'
                        )">
                    <i class="bi bi-eye"></i> Ver
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>