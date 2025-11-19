<?php
// _controllers/auth_controller.php

class AuthController {

    // ... (mostrarLogin, procesarLogin, mostrarRegistro, procesarRegistro, cerrarSesion se quedan igual) ...

    // Muestra la vista de Login
    public static function mostrarLogin() {
        $data = ["titulo_pagina" => "Iniciar Sesión"];
        require_once '_views/login.php';
    }

    // Procesa el formulario de Login
    public static function procesarLogin() {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $usuario = Usuario::verificarLogin($email, $password);
        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id_cliente'];
            $_SESSION['usuario_nombre'] = $usuario['nombres'];
            header("Location: index.php");
        } else {
            $error = "Correo o contraseña incorrectos.";
            $data = ["titulo_pagina" => "Iniciar Sesión", "error" => $error];
            require_once '_views/login.php';
        }
    }

    // Muestra la vista de Registro
    public static function mostrarRegistro() {
        $data = ["titulo_pagina" => "Registro"];
        require_once '_views/registro.php';
    }

    // Procesa el formulario de Registro
    public static function procesarRegistro() {
        $dni = $_POST['dni'];
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $referencia = $_POST['referencia'];
        $exito = Usuario::registrar($dni, $nombres, $apellidos, $email, $password, $telefono, $direccion, $referencia);
        if ($exito) {
            header("Location: index.php?action=login&status=success");
        } else {
            $error = "Error al registrar. Es posible que el DNI o Email ya exista.";
            $data = ["titulo_pagina" => "Registro", "error" => $error];
            require_once '_views/registro.php';
        }
    }

    // Cierra la sesión
    public static function cerrarSesion() {
        session_destroy();
        header("Location: index.php");
    }

    // --- NUEVAS FUNCIONES PARA "MI PERFIL" ---

    /**
     * Muestra la página de "Mi Perfil" con los datos del usuario
     */
    public static function mostrarMiCuenta() {
        // 1. Proteger la ruta, si no está logueado, fuera
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        // 2. Obtener los datos actuales del usuario
        $usuario = Usuario::obtenerPorId($_SESSION['usuario_id']);
        
        // 3. Preparar los datos para la vista
        $data = [
            "titulo_pagina" => "Mi Perfil",
            "usuario" => $usuario
        ];

        // 4. Cargar la vista
        require_once '_views/mi_cuenta.php';
    }

    /**
     * Procesa el formulario de actualización de datos personales
     */
    public static function actualizarPerfil() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
        
        $id_cliente = $_SESSION['usuario_id'];
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $referencia = $_POST['referencia'];

        $exito = Usuario::actualizarPerfil($id_cliente, $nombres, $apellidos, $telefono, $direccion, $referencia);

        // Actualizamos el nombre en la sesión por si lo cambió
        $_SESSION['usuario_nombre'] = $nombres;

        if ($exito) {
            header("Location: index.php?action=mi-cuenta&status=perfil_ok");
        } else {
            header("Location: index.php?action=mi-cuenta&status=error");
        }
    }

    /**
     * Procesa el formulario de cambio de contraseña
     */
    public static function actualizarPassword() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $id_cliente = $_SESSION['usuario_id'];
        $password_nueva = $_POST['password_nueva'];
        $password_repetir = $_POST['password_repetir'];

        // Validación simple
        if (empty($password_nueva) || $password_nueva != $password_repetir) {
            header("Location: index.php?action=mi-cuenta&status=pass_error_match");
            exit;
        }

        $exito = Usuario::actualizarPassword($id_cliente, $password_nueva);

        if ($exito) {
            header("Location: index.php?action=mi-cuenta&status=pass_ok");
        } else {
            header("Location: index.php?action=mi-cuenta&status=error");
        }
    }
}