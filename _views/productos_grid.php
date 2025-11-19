<h2 class="mb-4">
    <?php echo htmlspecialchars($data['nombre_categoria_actual']); ?> 
    (<?php echo count($data['productos']); ?> productos)
</h2>

<div class="row g-3">
    <?php if (count($data['productos']) > 0): ?>
        <?php foreach ($data['productos'] as $fila): ?>
            
            <div class="col-12 col-md-6 col-lg-4">
                
                <div class="card h-100" id="prod-<?php echo $fila['id_producto']; ?>">
                    
                    <img src="<?php echo htmlspecialchars($fila['imagen_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($fila['nombre_prod']); ?>">
                    
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title"><?php echo htmlspecialchars($fila['nombre_prod']); ?></h3>
                        <p class="precio mt-auto">S/ <?php echo number_format($fila['precio'], 2); ?></p>
                    </div>

                    <div class="card-footer control-cantidad">
                        <?php if ($fila['stock'] > 0): ?>
                            <button class="boton-anadir btn btn-success w-100" 
                                    onclick="agregarAlCarrito(
                                        <?php echo $fila['id_producto']; ?>, 
                                        '<?php echo addslashes(htmlspecialchars($fila['nombre_prod'])); ?>', 
                                        <?php echo $fila['precio']; ?>,
                                        <?php echo $fila['stock']; ?>
                                    )">
                                <i class="bi bi-plus-lg"></i> Añadir
                            </button>
                            
                            <div class="cantidad-input input-group" style="display: none;">
                                <button class="btn btn-outline-secondary" onclick="decrementarCantidad(<?php echo $fila['id_producto']; ?>)">-</button>
                                <span class="cantidad-texto form-control text-center">1</span>
                                <button class="btn btn-outline-secondary" onclick="incrementarCantidad(<?php echo $fila['id_producto']; ?>)">+</button>
                            </div>
                        <?php else: ?>
                            <button class="boton-agotado btn btn-danger w-100" disabled>Agotado</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div> <?php endforeach; ?>
    <?php else: ?>
        <p>No se encontraron productos en esta categoría.</p>
    <?php endif; ?>
</div>