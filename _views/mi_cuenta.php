<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['titulo_pagina']); ?> - OK Supermercados</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="_assets/css/style.css?v=1.5">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <header>
        <?php 
        // Necesitamos $data para que el header no falle
        $data['id_categoria_seleccionada'] = 'mi-cuenta'; // Para que no resalte ninguna categoría
        require_once '_views/partials/header_top.php'; 
        require_once '_views/partials/header_main.php'; 
        ?>
    </header>
    
    <nav class="nav-categorias navbar navbar-expand-lg">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="bi bi-arrow-left"></i> Volver a la Tienda
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <h1 class="h2 mb-4">Mi Perfil</h1>

                <?php if(isset($_GET['status'])): ?>
                    <?php if($_GET['status'] == 'perfil_ok'): ?>
                        <div class="alert alert-success">Datos actualizados correctamente.</div>
                    <?php elseif($_GET['status'] == 'pass_ok'): ?>
                        <div class="alert alert-success">Contraseña cambiada con éxito.</div>
                    <?php elseif($_GET['status'] == 'pass_error_match'): ?>
                        <div class="alert alert-danger">Las contraseñas no coinciden.</div>
                    <?php elseif($_GET['status'] == 'error'): ?>
                        <div class="alert alert-danger">Hubo un error al guardar los datos.</div>
                    <?php endif; ?>
                <?php endif; ?>

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="perfil-tab" data-bs-toggle="tab" data-bs-target="#perfil" type="button" role="tab" aria-controls="perfil" aria-selected="true">
                            Datos Personales
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">
                            Cambiar Contraseña
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content card shadow-sm border-top-0 rounded-bottom">
                    
                    <div class="tab-pane fade show active p-4" id="perfil" role="tabpanel" aria-labelledby="perfil-tab">
                        <form action="index.php?action=actualizar-perfil" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($data['usuario']['email']); ?>" disabled readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dni" class="form-label">DNI</label>
                                    <input type="text" class="form-control" id="dni" value="<?php echo htmlspecialchars($data['usuario']['dni']); ?>" disabled readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombres" class="form-label">Nombres</label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo htmlspecialchars($data['usuario']['nombres']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="apellidos" class="form-label">Apellidos</label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($data['usuario']['apellidos']); ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($data['usuario']['telefono']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección de Envío</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="2"><?php echo htmlspecialchars($data['usuario']['direccion']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="referencia" class="form-label">Referencia</label>
                                <textarea class="form-control" id="referencia" name="referencia" rows="2"><?php echo htmlspecialchars($data['usuario']['referencia']); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-success auth-button">Guardar Cambios</button>
                        </form>
                    </div>
                    
                    <div class="tab-pane fade p-4" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <form action="index.php?action=actualizar-password" method="POST">
                            <div class="mb-3">
                                <label for="password_nueva" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="password_nueva" name="password_nueva" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_repetir" class="form-label">Repetir Nueva Contraseña</label>
                                <input type="password" class="form-control" id="password_repetir" name="password_repetir" required>
                            </div>
                            <button type="submit" class="btn btn-secondary auth-button">Cambiar Contraseña</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="_assets/js/main.js?v=1.5"></script> 
</body>
</html>