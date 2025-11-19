<?php
// Datos para el layout
$data['titulo_pagina'] = 'Iniciar Sesión';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['titulo_pagina']; ?> - OK Supermercados</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="_assets/css/style.css?v=1.5">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <header>
        <?php 
        // Necesitamos $data (vacío) para que el header no falle
        if (!isset($data)) $data = ['id_categoria_seleccionada' => '']; 
        require_once '_views/partials/header_top.php'; 
        require_once '_views/partials/header_main.php'; 
        ?>
    </header>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h1 class="h3 text-center text-success mb-4">Iniciar Sesión</h1>
                        
                        <?php if (isset($data['error'])): // $data viene del controlador si hay error ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($data['error']); ?></div>
                        <?php endif; ?>
                        
                        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                            <div class="alert alert-success">¡Registro exitoso! Por favor, inicia sesión.</div>
                        <?php endif; ?>

                        <form action="index.php?action=do-login" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100 btn-lg auth-button">Ingresar</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            ¿No tienes una cuenta? <a href="index.php?action=registro" class="text-success">Regístrate aquí</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="_assets/js/main.js?v=1.5"></script> 
</body>
</html>