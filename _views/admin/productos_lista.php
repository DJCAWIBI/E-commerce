<a href="admin.php?action=producto_nuevo" class="btn btn-crear">
    <i class="bi bi-plus-circle"></i> Crear Nuevo Producto
</a>

<?php if(isset($_GET['status']) && $_GET['status'] == 'delete_error'): ?>
    <div class="alert-error" style="margin-top: 20px;">
        Error: No se puede eliminar el producto porque está asociado a uno o más pedidos.
    </div>
<?php endif; ?>

<table class="admin-table">
    <thead>
        <tr>
            <th>SKU</th>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['productos'] as $prod): ?>
        <tr>
            <td><?php echo htmlspecialchars($prod['sku']); ?></td>
            <td>
                <img src="<?php echo htmlspecialchars($prod['imagen_url']); ?>" alt="<?php echo htmlspecialchars($prod['nombre_prod']); ?>">
            </td>
            <td><?php echo htmlspecialchars($prod['nombre_prod']); ?></td>
            <td><?php echo htmlspecialchars($prod['nombre_categoria']); ?></td>
            <td>S/ <?php echo number_format($prod['precio'], 2); ?></td>
            <td><?php echo $prod['stock']; ?></td>
            <td class="acciones">
                <a href="admin.php?action=producto_editar&id=<?php echo $prod['id_producto']; ?>" class="btn btn-editar">
                    <i class="bi bi-pencil"></i>
                </a>
                <a href="admin.php?action=producto_eliminar&id=<?php echo $prod['id_producto']; ?>" 
                   class="btn btn-eliminar" 
                   onclick="return confirm('¿Estás seguro de que quieres eliminar este producto? Esta acción no se puede deshacer.');">
                    <i class="bi bi-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>