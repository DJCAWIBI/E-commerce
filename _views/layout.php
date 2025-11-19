<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermercado OK - Iquitos</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="_assets/css/style.css?v=1.5"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <script src="https://www.paypal.com/sdk/js?client-id=AWWcqQgpIaQkeK3hPg1Lxi1ernlMWT4YHPYkxrF6kznHQe2ZT6mpK28u3KRk7Tbqz3d-FooXiY60ss9M"></script>

</head>
<body>
    
    <header>
        <?php require_once '_views/partials/header_top.php'; ?>
        <?php require_once '_views/partials/header_main.php'; ?>
    </header>
    
    <nav class="nav-categorias navbar navbar-expand-lg">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navCategorias" aria-controls="navCategorias" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navCategorias">
                <ul class="navbar-nav justify-content-center w-100">
                    <li class="nav-item">
                        <a href="index.php?cat=all" 
                           class="nav-link <?php echo ($data['id_categoria_seleccionada'] == 'all') ? 'active' : ''; ?>">
                           Todos los Productos
                        </a>
                    </li>
                    <?php foreach ($data['categorias_menu'] as $cat): ?>
                        <li class="nav-item">
                            <a href="index.php?cat=<?php echo $cat['id_categoria']; ?>"
                               class="nav-link <?php echo ($data['id_categoria_seleccionada'] == $cat['id_categoria']) ? 'active' : ''; ?>">
                               <?php echo htmlspecialchars($cat['nombre_cat']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <div class="row">
            
            <main class="col-lg-9">
                <?php require_once '_views/productos_grid.php'; ?>
            </main>

            <aside class="col-lg-3">
                <div class="sidebar-carrito">
                    <div class="carrito-header">
                        <h3>Mi Carrito</h3>
                        <span id="carrito-cantidad-total">(0 productos)</span>
                    </div>
                    <div id="lista-carrito">
                        <p class="carrito-vacio-msg">Tu carrito está vacío</p>
                    </div>
                    <div class="carrito-total">
                        <span>TOTAL:</span>
                        <span id="carrito-total">S/ 0.00</span>
                    </div>
                    <p id="mensaje-minimo">Monto mínimo para delivery gratis: S/ 50.00</p>
                    <div class="carrito-cupon input-group mb-3">
                        <input type="text" id="cupon-input" class="form-control" placeholder="Escribe tu cupón">
                        <button id="aplicar-cupon-btn" class="btn btn-secondary">Aplicar</button>
                    </div>
                    <div id="paypal-button-container"></div>
                </div>
            </aside>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="_assets/js/main.js?v=1.6"></script> 
</body>
</html>