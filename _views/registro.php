<?php
// Datos para el layout
$data['titulo_pagina'] = 'Crear Cuenta';
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
        if (!isset($data)) $data = ['id_categoria_seleccionada' => '']; 
        require_once '_views/partials/header_top.php'; 
        require_once '_views/partials/header_main.php'; 
        ?>
    </header>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h1 class="h3 text-center text-success mb-4">Crear Cuenta</h1>
                        
                        <?php if (isset($data['error'])): // $data viene del controlador si hay error ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($data['error']); ?></div>
                        <?php endif; ?>

                        <form action="index.php?action=do-registro" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="dni" class="form-label">DNI</label>
                                    <input type="text" class="form-control" id="dni" name="dni" required maxlength="8">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombres" class="form-label">Nombres</label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="apellidos" class="form-label">Apellidos</label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="referencia" class="form-label">Referencia (Ej: Casa verde, rejas blancas)</label>
                                <textarea class="form-control" id="referencia" name="referencia" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100 btn-lg auth-button">Crear Cuenta</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            ¿Ya tienes una cuenta? <a href="index.php?action=login" class="text-success">Inicia Sesión</a>
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