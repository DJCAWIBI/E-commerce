// _assets/js/main.js

const montoMinimo = 50.00;
let carrito = JSON.parse(localStorage.getItem('carritoSupermercado')) || [];

/**
 * Función que se llama al cargar la página
 */
document.addEventListener('DOMContentLoaded', () => {
    actualizarCarritoHTML();
    actualizarTodasTarjetasProducto(); 
});

function guardarCarrito() {
    localStorage.setItem('carritoSupermercado', JSON.stringify(carrito));
}

// --- FUNCIONES DE MODIFICACIÓN DE CARRITO ---

function agregarAlCarrito(id, nombre, precio, stockDisponible) {
    const productoExistente = carrito.find(item => item.id === id);
    if (productoExistente) {
        if (productoExistente.cantidad < stockDisponible) {
            productoExistente.cantidad++;
        } else {
            alert('¡No puedes agregar más de este producto! Stock máximo alcanzado.');
        }
    } else {
        if (stockDisponible > 0) {
            carrito.push({
                id: id,
                nombre: nombre,
                precio: precio,
                cantidad: 1,
                stock: stockDisponible 
            });
        }
    }
    guardarCarrito();
    actualizarCarritoHTML();
    actualizarTarjetaProducto(id);
}

function incrementarCantidad(id) {
    const productoExistente = carrito.find(item => item.id === id);
    if (productoExistente) {
        if (!productoExistente.stock) {
             actualizarStockEnCarrito(id); 
             productoExistente = carrito.find(item => item.id === id); 
        }
        if (productoExistente.cantidad < productoExistente.stock) {
            productoExistente.cantidad++;
        } else {
            alert('¡No puedes agregar más de este producto! Stock máximo alcanzado.');
        }
        guardarCarrito();
        actualizarCarritoHTML();
        actualizarTarjetaProducto(id);
    }
}

function decrementarCantidad(id) {
    const productoExistente = carrito.find(item => item.id === id);
    if (productoExistente) {
        productoExistente.cantidad--;
        if (productoExistente.cantidad === 0) {
            carrito = carrito.filter(item => item.id !== id);
        }
        guardarCarrito();
        actualizarCarritoHTML();
        actualizarTarjetaProducto(id);
    }
}

function eliminarDelCarrito(id) {
    carrito = carrito.filter(item => item.id !== id);
    guardarCarrito();
    actualizarCarritoHTML();
    actualizarTarjetaProducto(id);
}

// --- FUNCIONES DE ACTUALIZACIÓN VISUAL (HTML) ---

function actualizarTodasTarjetasProducto() {
    const tarjetas = document.querySelectorAll('.tarjeta-producto');
    tarjetas.forEach(tarjeta => {
        const id = parseInt(tarjeta.id.split('-')[1]);
        actualizarStockEnCarrito(id);
        actualizarTarjetaProducto(id);
    });
    guardarCarrito();
}

function actualizarStockEnCarrito(id) {
    const itemEnCarrito = carrito.find(item => item.id === id);
    if (!itemEnCarrito) return; 
    const tarjeta = document.getElementById(`prod-${id}`);
    if (!tarjeta) return;
    const btnAnadir = tarjeta.querySelector('.boton-anadir');
    if (btnAnadir) {
        try {
            const onclickArgs = btnAnadir.onclick.toString().match(/\(([^)]+)\)/)[1];
            const stock = parseInt(onclickArgs.split(',')[3].trim());
            itemEnCarrito.stock = stock;
        } catch (e) {
            console.warn("No se pudo leer el stock de la tarjeta " + id);
        }
    } else {
        itemEnCarrito.stock = 0;
    }
}

function actualizarTarjetaProducto(id) {
    const productoEnCarrito = carrito.find(item => item.id === id);
    const tarjeta = document.getElementById(`prod-${id}`);
    if (!tarjeta) return;
    const btnAnadir = tarjeta.querySelector('.boton-anadir');
    const controlCantidad = tarjeta.querySelector('.cantidad-input');
    const textoCantidad = tarjeta.querySelector('.cantidad-texto');
    const btnAgotado = tarjeta.querySelector('.boton-agotado');
    if (btnAgotado) return;
    if (productoEnCarrito && productoEnCarrito.cantidad > 0) {
        if(btnAnadir) btnAnadir.style.display = 'none';
        if(controlCantidad) controlCantidad.style.display = 'flex';
        if(textoCantidad) textoCantidad.innerText = productoEnCarrito.cantidad;
    } else {
        if(btnAnadir) btnAnadir.style.display = 'block';
        if(controlCantidad) controlCantidad.style.display = 'none';
    }
}

function actualizarCarritoHTML() {
    const listaCarritoDiv = document.getElementById('lista-carrito');
    const totalSpan = document.getElementById('carrito-total');
    const mensajeMinimoP = document.getElementById('mensaje-minimo');
    const cantidadTotalSpan = document.getElementById('carrito-cantidad-total');
    listaCarritoDiv.innerHTML = '';
    let total = 0;
    let cantidadTotalProductos = 0;
    if (carrito.length === 0) {
        listaCarritoDiv.innerHTML = '<p class="carrito-vacio-msg">Tu carrito está vacío</p>';
    } else {
        carrito.forEach(item => {
            const itemHTML = document.createElement('div');
            itemHTML.classList.add('carrito-item');
            const nombreCorto = item.nombre.length > 20 ? item.nombre.substring(0, 20) + '...' : item.nombre;
            itemHTML.innerHTML = `
                <span class="carrito-item-nombre">${nombreCorto}</span>
                <div class="cantidad-input" style="border:none; height: 25px; width: 80px;">
                    <button class="btn-cantidad" style="height: 25px; width: 25px;" onclick="decrementarCantidad(${item.id})">-</button>
                    <span class="cantidad-texto">${item.cantidad}</span>
                    <button class="btn-cantidad" style="height: 25px; width: 25px;" onclick="incrementarCantidad(${item.id})">+</button>
                </div>
                <span class="carrito-item-precio">S/ ${(item.precio * item.cantidad).toFixed(2)}</span>
                <button class="carrito-item-quitar" onclick="eliminarDelCarrito(${item.id})">X</button>
            `;
            listaCarritoDiv.appendChild(itemHTML);
            total += item.precio * item.cantidad;
            cantidadTotalProductos += item.cantidad;
        });
    }
    totalSpan.innerText = `S/ ${total.toFixed(2)}`;
    cantidadTotalSpan.innerText = `(${cantidadTotalProductos} productos)`;
    if (total >= montoMinimo) {
        mensajeMinimoP.innerText = '¡Tienes delivery gratis!';
        mensajeMinimoP.style.color = '#218838';
    } else {
        const faltante = montoMinimo - total;
        mensajeMinimoP.innerText = `Te falta S/ ${faltante.toFixed(2)} para delivery gratis`;
        mensajeMinimoP.style.color = '#b94a48';
    }
}

// ======================================================
// Inicialización de los Botones de PayPal
// ======================================================
paypal.Buttons({
    createOrder: function(data, actions) {
        let total = 0;
        carrito.forEach(item => { total += item.precio * item.cantidad; });
        const tipoCambio = 3.8; 
        let totalUSD = (total / tipoCambio).toFixed(2);
        if (totalUSD < 1.0) { totalUSD = 1.00; }
        return actions.order.create({
            purchase_units: [{ amount: { value: totalUSD, currency_code: 'USD' } }]
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            console.log('Pago completado:', details);
            alert('¡Pago (simulado) exitoso! Procesando tu pedido...');
            enviarPedidoAlServidor(details.id);
        });
    },
    onError: function(err) {
        console.error('Error en la pasarela de PayPal:', err);
        alert('Hubo un error al procesar tu pago con PayPal.');
    }
}).render('#paypal-button-container');

/**
 * Envía el carrito a nuestro servidor (PHP)
 */
async function enviarPedidoAlServidor(paypalOrderID) {
    try {
        const respuesta = await fetch('index.php?action=procesar-compra', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                carrito: carrito,
                paypal_id: paypalOrderID
            })
        });
        const data = await respuesta.json();
        if (data.success) {
            alert(data.message);
            carrito = [];
            guardarCarrito();
            actualizarCarritoHTML();
            actualizarTodasTarjetasProducto();
            actualizarDropdownPedidos(data.pedido);
        } else {
            if (data.error === 'no_stock') {
                alert(data.message);
                location.reload(); 
            } else if (data.error === 'login_required') {
                alert('Debes iniciar sesión para procesar tu compra.');
                window.location.href = 'index.php?action=login';
            } else {
                alert('Hubo un error al guardar tu pedido en nuestra base de datos.');
            }
        }
    } catch (error) {
        console.error('Error en fetch:', error);
        alert('Error de conexión al guardar el pedido.');
    }
}


/**
 * Inserta el nuevo pedido en el desplegable del header sin recargar.
 * (MODIFICADO para usar las clases de Bootstrap)
 */
function actualizarDropdownPedidos(pedido) {
    // 1. Buscamos el contenedor del desplegable (AHORA BUSCA .dropdown-menu)
    const dropdown = document.querySelector('#btn-pedidos .dropdown-menu'); // <-- ¡CAMBIO AQUÍ!
    if (!dropdown) return; 

    // 2. Buscamos el mensaje de "No tienes pedidos" (AHORA BUSCA p.dropdown-item-text)
    const noPedidosMsg = dropdown.querySelector('p.dropdown-item-text'); // <-- ¡CAMBIO AQUÍ!
    if (noPedidosMsg) {
        noPedidosMsg.remove(); 
    }

    // 3. Formateamos los datos
    const fecha = new Date(pedido.fecha_pedido);
    const fechaFormato = fecha.toLocaleDateString('es-PE', {day: '2-digit', month: '2-digit', year: 'numeric'});
    const totalFormato = parseFloat(pedido.total).toFixed(2);

    // 4. Creamos el HTML para el nuevo ítem (con las clases de Bootstrap)
    const nuevoPedidoHTML = `
        <div class="pedido-item dropdown-item-text">
            <strong>Pedido #${pedido.id_pedido}</strong>
            <span>${fechaFormato}</span>
            <span>S/ ${totalFormato}</span>
            <em>(${pedido.estado})</em>
        </div>
    `;

    // 5. Insertamos el nuevo pedido al PRINCIPIO del desplegable
    dropdown.insertAdjacentHTML('afterbegin', nuevoPedidoHTML);
}