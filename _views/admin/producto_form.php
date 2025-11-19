<?php
// Lógica para rellenar el formulario (si es modo "Editar")
$es_editar = isset($data['producto']) && $data['producto'];
$producto = $data['producto'];
?>

<form class="admin-form" action="admin.php?action=producto_guardar" method="POST">

    <?php if ($es_editar): ?>
        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
    <?php endif; ?>

    <div class="form-group">
        <label for="nombre_prod">Nombre del Producto</label>
        <input type="text" id="nombre_prod" name="nombre_prod" value="<?php echo $es_editar ? htmlspecialchars($producto['nombre_prod']) : ''; ?>" required>
    </div>
    
    <div class="form-group">
        <label for="id_categoria">Categoría</label>
        <select id="id_categoria" name="id_categoria" required>
            <option value="">-- Seleccione una subcategoría --</option>
            <?php foreach ($data['subcategorias'] as $cat): ?>
                <option value="<?php echo $cat['id_categoria']; ?>" <?php echo ($es_editar && $producto['id_categoria'] == $cat['id_categoria']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['nombre_cat']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="descripcion_prod">Descripción</label>
        <textarea id="descripcion_prod" name="descripcion_prod"><?php echo $es_editar ? htmlspecialchars($producto['descripcion_prod']) : ''; ?></textarea>
    </div>

    <div class="form-group">
        <label for="precio">Precio (S/)</label>
        <input type="number" step="0.01" id="precio" name="precio" value="<?php echo $es_editar ? $producto['precio'] : ''; ?>" required>
    </div>

    <div class="form-group">
        <label for="stock">Stock (Inventario)</label>
        <input type="number" id="stock" name="stock" value="<?php echo $es_editar ? $producto['stock'] : ''; ?>" required>
    </div>

    <div class="form-group">
        <label for="sku">SKU (Código)</label>
        <input type="text" id="sku" name="sku" value="<?php echo $es_editar ? htmlspecialchars($producto['sku']) : ''; ?>">
    </div>

    <div class="form-group">
        <label for="imagen_url">URL de la Imagen</label>
        <input type="text" id="imagen_url" name="imagen_url" value="<?php echo $es_editar ? htmlspecialchars($producto['imagen_url']) : ''; ?>">
    </div>

    <button type="submit" class="form-submit">
        <?php echo $es_editar ? 'Actualizar Producto' : 'Crear Producto'; ?>
    </button>
    <a href="admin.php?action=productos" style="margin-left: 15px;">Cancelar</a>

</form>