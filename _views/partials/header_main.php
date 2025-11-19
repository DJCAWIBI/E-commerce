<div class="header-main">
    <div class="container">
        <div class="row align-items-center g-3">
            
            <div class="col-lg-3 col-md-4 col-12 text-center text-md-start">
                <a href="index.php?cat=all" class="header-logo">
                    <img src="_assets/img/oklogo.jpg" alt="Logo OK Supermercados">
                </a>
            </div>
            
            <div class="col-lg-5 col-md-8 col-12">
                <form class="header-search input-group" action="index.php" method="GET">
                    <input type="hidden" name="action" value="search">
                    <input type="text" name="query" class="form-control" placeholder="¿Qué estás buscando hoy?" required>
                    <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                </form>
            </div>

            <div class="col-lg-4 col-12">
                <div class="header-actions d-flex justify-content-center justify-content-lg-end">
                    
                    <a href="conocenos.php" target="_blank" class="action-button d-none d-lg-flex">
                        <i class="bi bi-info-circle"></i>
                        <span>Conócenos</span>
                    </a>
                    
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        
                        <div class="action-button action-dropdown dropdown" id="btn-pedidos">
                            <a href="#" class="d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-receipt"></i>
                                <span class="d-none d-lg-block">Mis Pedidos</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-pedidos">
                                <?php $pedidos = Pedido::obtenerPedidosPorCliente($_SESSION['usuario_id']); ?>
                                <?php if (count($pedidos) > 0): ?>
                                    <?php foreach ($pedidos as $pedido): ?>
                                        <div class="pedido-item dropdown-item-text">
                                            <strong>Pedido #<?php echo $pedido['id_pedido']; ?></strong>
                                            <span><?php echo date('d/m/Y', strtotime($pedido['fecha_pedido'])); ?></span>
                                            <span>S/ <?php echo number_format($pedido['total'], 2); ?></span>
                                            <em>(<?php echo htmlspecialchars($pedido['estado']); ?>)</em>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="dropdown-item-text">No tienes pedidos anteriores.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="action-button action-dropdown dropdown" id="btn-cuenta">
                            <a href="#" class="d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-check-fill"></i>
                                <span class="d-none d-lg-block">Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="index.php?action=mi-cuenta" class="dropdown-item">Mi Perfil</a>
                                <a href="index.php?action=logout" class="dropdown-item text-danger">Cerrar Sesión</a>
                            </div>
                        </div>

                    <?php else: ?>

                        <div class="action-button action-dropdown dropdown" id="btn-pedidos-visitante">
                            <a href="#" class="d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-receipt"></i>
                                <span class="d-none d-lg-block">Mis Pedidos</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-pedidos">
                                <p class="dropdown-item-text">Inicia sesión para ver tu historial.</p>
                            </div>
                        </div>

                        <a href="index.php?action=login" class="action-button" id="btn-login">
                            <i class="bi bi-person"></i>
                            <span>Ingresar</span>
                        </a>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>