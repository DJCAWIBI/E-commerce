<?php
$es_editar = isset($data['categoria']) && $data['categoria'];
$categoria = $data['categoria'];
?>

<form class="admin-form" action="admin.php?action=categoria_guardar" method="POST">

    <?php if ($es_editar): ?>
        <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">
    <?php endif; ?>

    <div class="form-group">
        <label for="nombre_cat">Nombre de la Categoría</label>
        <input type="text" id="nombre_cat" name="nombre_cat" value="<?php echo $es_editar ? htmlspecialchars($categoria['nombre_cat']) : ''; ?>" required>
    </div>

    <div class="form-group">
        <label for="id_categoria_padre">Categoría Padre</label>
        <select id="id_categoria_padre" name="id_categoria_padre">
            <option value="null">-- Ninguna (Es una Categoría Principal) --</option>
            
            <?php foreach ($data['categorias_padre'] as $padre): ?>
                <?php if ($es_editar && $padre['id_categoria'] == $categoria['id_categoria']) continue; ?>
                
                <option value="<?php echo $padre['id_categoria']; ?>" <?php echo ($es_editar && $categoria['id_categoria_padre'] == $padre['id_categoria']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($padre['nombre_cat']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="descripcion_cat">Descripción</label>
        <textarea id="descripcion_cat" name="descripcion_cat"><?php echo $es_editar ? htmlspecialchars($categoria['descripcion_cat']) : ''; ?></textarea>
    </div>

    <button type="submit" class="form-submit">
        <?php echo $es_editar ? 'Actualizar Categoría' : 'Crear Categoría'; ?>
    </button>
    <a href="admin.php?action=categorias" style="margin-left: 15px;">Cancelar</a>

</form>