<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['titulo_pagina']; ?> - Admin OK</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f8fb; }
        .admin-sidebar {
            width: 250px; background-color: #2c3e50; color: white;
            position: fixed; height: 100%; padding-top: 20px;
        }
        .admin-sidebar h2 {
            text-align: center; color: #fff; padding-bottom: 20px;
            border-bottom: 1px solid #34495e;
        }
        .admin-sidebar nav a {
            display: block; padding: 15px 25px; color: #ecf0f1;
            text-decoration: none; font-size: 16px;
            border-bottom: 1px solid #34495e;
        }
        .admin-sidebar nav a:hover, .admin-sidebar nav a.activo {
            background-color: #218838; /* Verde OK */
        }
        .admin-main-content { margin-left: 250px; padding: 20px; }
        .admin-header {
            display: flex; justify-content: space-between; align-items: center;
            background-color: #fff; padding: 15px 30px; border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 20px;
        }
        .admin-header span { font-size: 18px; font-weight: bold; }
        .admin-header a { color: #b94a48; text-decoration: none; font-weight: bold; }
        .content-box {
            background-color: #fff; padding: 30px; border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .admin-table {
            width: 100%; border-collapse: collapse; margin-top: 20px;
        }
        .admin-table th, .admin-table td {
            border: 1px solid #ddd; padding: 12px; text-align: left;
        }
        .admin-table th { background-color: #f4f8fb; }
        .admin-table td img { max-width: 50px; height: auto; }
        .admin-table .acciones a, .admin-table .acciones button { margin-right: 10px; }
        .btn {
            display: inline-block; padding: 8px 15px; border-radius: 5px;
            text-decoration: none; color: white; font-weight: bold;
        }
        .btn-crear { background-color: #218838; }
        .btn-editar { background-color: #007bff; }
        .btn-eliminar { background-color: #b94a48; }
        .admin-form .form-group { margin-bottom: 20px; }
        .admin-form .form-group label {
            display: block; margin-bottom: 5px; font-weight: bold;
        }
        .admin-form .form-group input,
        .admin-form .form-group textarea,
        .admin-form .form-group select {
            width: 100%; padding: 10px; border: 1px solid #ccc;
            border-radius: 5px; box-sizing: border-box; font-size: 14px;
        }
        .admin-form .form-group textarea { min-height: 100px; }
        .admin-form .form-submit {
            background-color: #218838; color: white; font-size: 16px;
            padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer;
        }
        .alert-error {
            padding: 15px; background-color: #f8d7da; color: #721c24;
            border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <aside class="admin-sidebar">
        <h2>OK Admin</h2>
        <nav>
            <a href="admin.php?action=dashboard" class="<?php echo ($data['action'] == 'dashboard') ? 'activo' : ''; ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="admin.php?action=productos" class="<?php echo (strpos($data['action'], 'producto') !== false) ? 'activo' : ''; ?>">
                <i class="bi bi-box-seam"></i> Productos
            </a>
            <a href="admin.php?action=pedidos" class="<?php echo ($data['action'] == 'pedidos') || ($data['action'] == 'pedido_detalle_ajax') ? 'activo' : ''; ?>">
                <i class="bi bi-receipt"></i> Pedidos
            </a>
            <a href="admin.php?action=categorias" class="<?php echo (strpos($data['action'], 'categoria') !== false) ? 'activo' : ''; ?>">
                <i class="bi bi-tags"></i> Categorías
            </a>
        </nav>
    </aside>

    <main class="admin-main-content">
        
        <header class="admin-header">
            <span><?php echo htmlspecialchars($data['titulo_pagina']); ?></span>
            <a href="admin.php?action=logout">Cerrar Sesión</a>
        </header>

        <div class="content-box">
            <?php
            // --- Carga de Vistas ---
            if ($data['action'] == 'dashboard') {
                require_once '_views/admin/dashboard.php';
            } 
            elseif ($data['action'] == 'productos') {
                require_once '_views/admin/productos_lista.php';
            } elseif ($data['action'] == 'producto_nuevo' || $data['action'] == 'producto_editar') {
                require_once '_views/admin/producto_form.php';
            }
            elseif ($data['action'] == 'categorias') {
                require_once '_views/admin/categorias_lista.php';
            } elseif ($data['action'] == 'categoria_nueva' || $data['action'] == 'categoria_editar') {
                require_once '_views/admin/categoria_form.php';
            }
            elseif ($data['action'] == 'pedidos') {
                require_once '_views/admin/pedidos_lista.php';
            }
            ?>
        </div>

    </main>

    <div class="modal fade" id="detallePedidoModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Detalle del Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modal-info-cliente"></p>
                    <div id="modal-contenido-detalle">
                        <p>Cargando detalles...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <h4 id="modal-total" style="margin-right: auto;"></h4>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Creamos una instancia del Modal de Bootstrap
        const detalleModal = new bootstrap.Modal(document.getElementById('detallePedidoModal'));

        async function mostrarDetallePedido(idPedido, nombreCliente, totalPedido) {
            
            // 1. Obtenemos las partes del modal
            const modalTitulo = document.getElementById('modalLabel');
            const modalInfoCliente = document.getElementById('modal-info-cliente');
            const modalContenido = document.getElementById('modal-contenido-detalle');
            const modalTotal = document.getElementById('modal-total');

            // 2. Llenamos la info básica que ya tenemos
            modalTitulo.innerText = `Detalle del Pedido #${idPedido}`;
            modalInfoCliente.innerText = `Cliente: ${nombreCliente}`;
            modalTotal.innerText = `Total: S/ ${totalPedido}`;
            modalContenido.innerHTML = '<p>Cargando detalles...</p>';
            
            // 3. Mostramos el modal
            detalleModal.show();

            // 4. Buscamos los detalles por AJAX (fetch)
            try {
                const respuesta = await fetch(`admin.php?action=pedido_detalle_ajax&id=${idPedido}`);
                const detalles = await respuesta.json();

                if (detalles.error) {
                    throw new Error(detalles.error);
                }

                // 5. Construimos la tabla de detalles
                let tablaHtml = `
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Producto</th>
                                <th>Cant.</th>
                                <th>Precio Unit.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                detalles.forEach(item => {
                    const subtotal = parseFloat(item.cantidad) * parseFloat(item.precio_unitario);
                    tablaHtml += `
                        <tr>
                            <td>${item.sku}</td>
                            <td>${item.nombre_prod}</td>
                            <td>${item.cantidad}</td>
                            <td>S/ ${parseFloat(item.precio_unitario).toFixed(2)}</td>
                            <td>S/ ${subtotal.toFixed(2)}</td>
                        </tr>
                    `;
                });

                tablaHtml += '</tbody></table>';
                
                // 6. Insertamos la tabla en el modal
                modalContenido.innerHTML = tablaHtml;

            } catch (error) {
                modalContenido.innerHTML = `<p class="text-danger">Error al cargar los detalles: ${error.message}</p>`;
            }
        }
    </script>

</body>
</html>