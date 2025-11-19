<a href="admin.php?action=categoria_nueva" class="btn btn-crear">
    <i class="bi bi-plus-circle"></i> Crear Nueva Categoría
</a>

<?php if(isset($_GET['status'])): ?>
    <?php if($_GET['status'] == 'error_productos'): ?>
        <div class="alert-error" style="margin-top: 20px;">
            Error: No se puede eliminar la categoría porque tiene productos asociados.
        </div>
    <?php elseif($_GET['status'] == 'error_hijas'): ?>
        <div class="alert-error" style="margin-top: 20px;">
            Error: No se puede eliminar la categoría porque tiene subcategorías asociadas.
        </div>
    <?php endif; ?>
<?php endif; ?>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Categoría Padre</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['categorias'] as $cat): ?>
        <tr>
            <td><?php echo $cat['id_categoria']; ?></td>
            <td><strong><?php echo htmlspecialchars($cat['nombre_cat']); ?></strong></td>
            <td>
                <?php if ($cat['nombre_padre']): ?>
                    <span style="color: #555;"><?php echo htmlspecialchars($cat['nombre_padre']); ?></span>
                <?php else: ?>
                    <span style="color: #218838; font-weight: bold;">-- Categoría Principal --</span>
                <?php endif; ?>
            </td>
            
            <td><?php echo htmlspecialchars($cat['descripcion_cat'] ?? ''); ?></td>
            
            <td class="acciones">
                <a href="admin.php?action=categoria_editar&id=<?php echo $cat['id_categoria']; ?>" class="btn btn-editar">
                    <i class="bi bi-pencil"></i>
                </a>
                <a href="admin.php?action=categoria_eliminar&id=<?php echo $cat['id_categoria']; ?>" 
                   class="btn btn-eliminar" 
                   onclick="return confirm('¿Estás seguro de que quieres eliminar esta categoría?');">
                    <i class="bi bi-trash"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>