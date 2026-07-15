let carrito = [];
let productoPendiente = null;
let suscripcionActiva = false;

const productos = {
  cocaCola: { descripcion: "Huevo Cola (no incluye gallina)", valor: 2.0 },
  fanta: { descripcion: "Regador que se riega a sí mismo", valor: 1.75 },
  sprit: { descripcion: "Rallador para comida imaginaria", valor: 1.5 },
  pepsi: { descripcion: "Espagueti de tenedor", valor: 1.8 },
  nestea: { descripcion: "Botas personales para charcos", valor: 2.25 },
};

$(function () {
  $("#slider").Thumbelina({
    $bwdBut: $("#slider .left"),
    $fwdBut: $("#slider .right"),
    orientation: "horizontal",
    step: 50,
  });

  const subscriptionForm = document.getElementById("subscription-form");
  const keyForm = document.getElementById("key-form");
  const invoiceForm = document.getElementById("invoice-form");
  if (subscriptionForm) subscriptionForm.addEventListener("submit", activarSuscripcion);
  if (keyForm) keyForm.addEventListener("submit", validarClave);
  if (invoiceForm) invoiceForm.addEventListener("submit", confirmarCompra);
  renderizarCarrito();
});

function imagenes(img) {
  document.getElementById("big-img").src = img.src;
  document.querySelectorAll(".imgc").forEach(function (imagen) {
    imagen.classList.remove("selected");
  });
  img.classList.add("selected");

  let datos = productos[img.alt];
  if (!datos) return;
  intentarAgregar({ id: img.alt, descripcion: datos.descripcion, valor: datos.valor });
}

function intentarAgregar(producto) {
  let existente = carrito.find(function (item) { return item.id === producto.id; });

  if (existente) {
    existente.cantidad++;
    mostrarMensaje("Otro " + existente.descripcion + ". Audaz decisión.");
    renderizarCarrito();
    irAlCarrito();
    return;
  }

  if (carrito.length >= 2 && !suscripcionActiva) {
    productoPendiente = producto;
    abrirSuscripcion();
    return;
  }

  agregarProducto(producto);
}

function agregarProducto(producto) {
  carrito.push({ id: producto.id, descripcion: producto.descripcion, valor: producto.valor, cantidad: 1 });
  mostrarMensaje(producto.descripcion + " cayó dentro del carrito.");
  renderizarCarrito();
  irAlCarrito();
}

function renderizarCarrito() {
  let cuerpo = document.getElementById("cart-body");
  cuerpo.innerHTML = "";

  if (carrito.length === 0) {
    cuerpo.innerHTML = '<tr><td colspan="5" class="empty-cart">El carrito está vacío y, por ahora, conserva su dignidad.</td></tr>';
  }

  carrito.forEach(function (producto) {
    let fila = document.createElement("tr");
    fila.innerHTML = `
      <td data-label="Producto"><strong>${producto.descripcion}</strong></td>
      <td data-label="Precio">$${producto.valor.toFixed(2)}</td>
      <td data-label="Cantidad"><input class="quantity-input" type="number" min="1" value="${producto.cantidad}"></td>
      <td data-label="Subtotal"><strong>$${(producto.valor * producto.cantidad).toFixed(2)}</strong></td>
      <td data-label="Acción"><button class="remove-button" type="button">Eliminar</button></td>`;

    fila.querySelector(".quantity-input").addEventListener("change", function () {
      cambiarCantidad(producto.id, this.value);
    });
    fila.querySelector(".remove-button").addEventListener("click", function () {
      eliminarProducto(producto.id);
    });
    cuerpo.appendChild(fila);
  });

  let total = calcularTotal();
  document.getElementById("grand-total").textContent = "$" + total.toFixed(2);
  document.getElementById("invoice-total").textContent = "$" + total.toFixed(2);
}

function cambiarCantidad(id, cantidad) {
  let producto = carrito.find(function (item) { return item.id === id; });
  if (producto) producto.cantidad = Math.max(1, parseInt(cantidad) || 1);
  renderizarCarrito();
}

function eliminarProducto(id) {
  carrito = carrito.filter(function (producto) { return producto.id !== id; });
  renderizarCarrito();
  mostrarMensaje("Producto eliminado. El carrito respira aliviado.");
}

function calcularTotal() {
  return carrito.reduce(function (total, producto) {
    return total + producto.valor * producto.cantidad;
  }, 0);
}

function agregarOtro() {
  document.getElementById("productos").scrollIntoView({ behavior: "smooth" });
  mostrarMensaje("Adelante, elige otra cosa que no necesitas.");
}

function irAlCarrito() {
  document.getElementById("compra").scrollIntoView({ behavior: "smooth", block: "start" });
}

function abrirSuscripcion() {
  mostrarPago();
  abrirModal("subscription-modal");
  setTimeout(function () { document.getElementById("card-name").focus(); }, 50);
}

function cerrarSuscripcion() {
  cerrarModal("subscription-modal");
  productoPendiente = null;
  mostrarMensaje("El tercer producto quedó afuera mirando por la ventana.");
}

function mostrarClave() {
  document.getElementById("payment-view").hidden = true;
  document.getElementById("key-view").hidden = false;
  document.getElementById("key-error").textContent = "";
  document.getElementById("access-key").focus();
}

function mostrarPago() {
  document.getElementById("payment-view").hidden = false;
  document.getElementById("key-view").hidden = true;
}

function activarSuscripcion(evento) {
  evento.preventDefault();
  completarSuscripcion("Pago imaginario aprobado. Tu banco ficticio está preocupado.");
  evento.target.reset();
}

function validarClave(evento) {
  evento.preventDefault();
  let clave = document.getElementById("access-key").value.trim().toUpperCase();
  if (clave !== "JHADITH2026") {
    document.getElementById("key-error").textContent = "Clave incorrecta. El guardia imaginario dijo que no.";
    return;
  }
  completarSuscripcion("Clave aceptada. Acceso VIP sospechosamente gratuito.");
  evento.target.reset();
}

function completarSuscripcion(mensaje) {
  suscripcionActiva = true;
  cerrarModal("subscription-modal");
  if (productoPendiente) agregarProducto(productoPendiente);
  productoPendiente = null;
  mostrarMensaje(mensaje);
}

function abrirFactura() {
  if (carrito.length === 0) {
    mostrarMensaje("Primero agrega algo absurdo al carrito.");
    return;
  }
  const total = calcularTotal();
  document.getElementById("invoice-total-value").value = total.toFixed(2);
  abrirModal("invoice-modal");
  setTimeout(function () { document.getElementById("cedula").focus(); }, 50);
}

function cerrarFactura() {
  cerrarModal("invoice-modal");
}

let ultimaFacturaId = null;

function confirmarCompra(evento) {
  evento.preventDefault();
  const cedula = document.getElementById("cedula").value.trim();
  const nombre = document.getElementById("nombre").value.trim();
  const total = calcularTotal();

  if (total === 0) {
    mostrarMensaje("No hay productos para comprar.");
    return;
  }
  if (!/^[0-9]{10}$/.test(cedula)) {
    alert("Ingresa una cédula válida de 10 dígitos.");
    document.getElementById("cedula").focus();
    return;
  }

  document.getElementById("invoice-total-value").value = total.toFixed(2);
  const formData = new FormData();
  formData.append("cedula", cedula);
  formData.append("nombre", nombre);
  formData.append("total", total.toFixed(2));

  fetch("guardar_compra.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        ultimaFacturaId = data.id;
        document.getElementById("download-button").hidden = false;
        document.getElementById("confirm-button").textContent = "Compra confirmada";
        mostrarMensaje("Compra registrada correctamente. Ya puedes descargar la factura.");
      } else {
        alert(data.message || "No se pudo guardar la compra. Intenta de nuevo.");
      }
    })
    .catch(() => {
      alert("Error de red al guardar la compra. Intenta más tarde.");
    });
}

function descargarFactura() {
  if (!ultimaFacturaId) {
    alert("No hay factura disponible para descargar.");
    return;
  }
  window.location.href = "download_invoice.php?id=" + encodeURIComponent(ultimaFacturaId);
}

function abrirModal(id) {
  let modal = document.getElementById(id);
  modal.classList.add("open");
  modal.setAttribute("aria-hidden", "false");
  document.body.classList.add("modal-open");
}

function cerrarModal(id) {
  let modal = document.getElementById(id);
  modal.classList.remove("open");
  modal.setAttribute("aria-hidden", "true");
  document.body.classList.remove("modal-open");
}

function mostrarMensaje(mensaje) {
  document.getElementById("cart-message").textContent = mensaje;
}
