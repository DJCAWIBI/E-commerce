<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - OK Supermercados</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="_assets/css/style.css?v=1.7">
    
    <style>
        body {
            background-color: #f4f8fb;
        }
        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="auth-container">
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="_assets/img/oklogo.jpg" alt="Logo OK" style="max-height: 60px;">
            </div>
            <h1 style="margin-bottom: 25px;">Panel de Administración</h1>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="admin.php?action=do-login" method="POST">
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="auth-button btn btn-success w-100 btn-lg">Ingresar</button>
            </form>
        </div>
    </div>

</body>
</html>