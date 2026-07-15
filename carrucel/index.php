<?php

include('servicios.php');
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cedula']) && isset($_POST['total'])) {
    $cedula = trim($_POST['cedula']);
    $total = floatval($_POST['total']);
    if (preg_match('/^[0-9]{10}$/', $cedula) && $total > 0) {
        $guardado = servicios::insertar($cedula, $total);
        $mensaje = $guardado ? 'Compra registrada correctamente.' : 'Error al guardar la compra. Intenta de nuevo.';
    } else {
        $mensaje = 'Por favor revisa la cédula y el total antes de enviar.';
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Tienda de bebidas de Jhadith">
  <title>Tienda de cosas sin sentido</title>
  <link rel="stylesheet" href="./css/thumbelina.css">
  <link rel="stylesheet" href="./css/style.css">
  <script src="./js/jquery-4.0.0.min.js" defer></script>
  <script src="./js/thumbelina.js" defer></script>
  <script src="./js/script.js" defer></script>
</head>
<body>
  <header class="header">
    <a class="brand" href="#inicio"><span>Tienda de cosas sin sentido</span></a>
    <nav class="nav" aria-label="Navegación principal">
      <a href="#inicio">Inicio</a><a href="#productos">Productos</a><a href="#compra">Mi compra</a>
    </nav>
  </header>

  <main id="inicio">

   

    <section class="store-section" id="productos">
      <div class="section-heading">
        <div><span class="eyebrow">Catálogo Jhadith</span><h2>Elige :)</h2></div>
        <p>Haz clic en un producto para agregarlo a tu carrito.</p>
      </div>
      <div class="product-showcase">
        <div class="preview-card">
          <span class="preview-label">Producto seleccionado</span>
          <img src="https://inmitacs.wordpress.com/wp-content/uploads/2011/11/guebo.jpg" alt="Bebida seleccionada" id="big-img">
          <p>Selecciona una miniatura para cambiar el producto.</p>
        </div>
        <div class="carousel-area">
          <div id="slider" aria-label="Carrusel de bebidas">
            <button class="thumbelina-but horiz left" type="button" aria-label="Producto anterior">&#10094;</button>
            <ul>
              <li><img src="https://inmitacs.wordpress.com/wp-content/uploads/2011/11/guebo.jpg" class="imgc selected" alt="cocaCola" onclick="imagenes(this)" id="img1"></li>
              <li><img src="https://ovacen.com/wp-content/uploads/2017/09/objetos-inutiles.jpg" class="imgc" alt="fanta" onclick="imagenes(this)" id="img2"></li>
              <li><img src="https://static.dezeen.com/uploads/2012/11/dezeen_Erratum-by-Jeremy-Hutchison_1sq.jpg" class="imgc" alt="sprit" onclick="imagenes(this)" id="img3"></li>
              <li><img src="https://ovacen.com/wp-content/uploads/2017/09/tenedor-de-diseno.jpg" class="imgc" alt="pepsi" onclick="imagenes(this)" id="img4"></li>
              <li><img src="https://theartgorgeous.com/wp-content/uploads/2020/01/2artist-_theartgorgeous.jpg" class="imgc" alt="nestea" onclick="imagenes(this)" id="img5"></li>
            </ul>
            <button class="thumbelina-but horiz right" type="button" aria-label="Producto siguiente">&#10095;</button>
          </div>
          <p class="carousel-help">Usa las flechas para descubrir más opciones.</p>
        </div>
      </div>
       
    </section>


    <section class="purchase-section" id="compra">
      <div class="section-heading compact">
        <div><span class="eyebrow">Carrito de compra</span><h2>Tu pedido</h2></div>
        <p id="cart-message" aria-live="polite">Selecciona el objeto para agregar</p>
      </div>
      <div class="table-wrapper">
        <table>
          <thead><tr><th>Producto</th><th>Valor unitario</th><th>Cantidad</th><th>Subtotal</th><th>Acción</th></tr></thead>
          <tbody id="cart-body"></tbody>
          <tfoot><tr><td colspan="3">Total de la compra</td><td id="grand-total">$0.00</td><td></td></tr></tfoot>
        </table>
      </div>
      <div class="purchase-actions">
      
        <div class="cart-buttons">
          <button class="secondary-button" type="button" onclick="agregarOtro()">+ Agregar otro</button>
          <button id="boton" class="primary-button" type="button" onclick="abrirFactura()">Comprar</button>
        </div>
      </div>
      <?php if (!empty($mensaje)): ?>
        <div class="notification"><?php echo htmlspecialchars($mensaje); ?></div>
      <?php endif; ?>
    </section>
  </main>

  <div class="modal" id="subscription-modal" aria-hidden="true">
    <div class="modal-backdrop" onclick="cerrarSuscripcion()"></div>
    <section class="modal-card subscription-card" role="dialog" aria-modal="true" aria-labelledby="subscription-title">
      <button class="modal-close" type="button" onclick="cerrarSuscripcion()" aria-label="Cerrar formulario">&times;</button>
      <span class="eyebrow">Suscripción requerida</span>
      <h2 id="subscription-title">Activa tu suscripción</h2>
      <p>Para agregar un tercer producto necesitas activar la suscripción gratuita.</p>
      <div id="payment-view">
        <form id="subscription-form">
          <label for="card-name">Numero de tarjeta</label>
          <input type="text" id="card-name" name="card-name" minlength="3" placeholder="4345 2345 2345 4567" required>
          <div class="form-row">
            <div>
              <label for="card-email">Correo electrónico</label>
              <input type="email" id="card-email" name="card-email" placeholder="ejemplo@mail.com" required>
            </div>
          </div>
          <button class="primary-button" type="submit">Pagar suscripción</button>
          <button class="secondary-button" type="button" onclick="mostrarClave()">Ya tengo clave</button>
        </form>
      </div>
      <div id="key-view" hidden>
        <form id="key-form">
          <label for="access-key">Clave de acceso</label>
          <input type="text" id="access-key" name="access-key" placeholder="JHADITH2026" required>
          <p id="key-error" class="security-note"></p>
          <button class="primary-button" type="submit">Validar clave</button>
        </form>
      </div>
    </section>
  </div>

  <div class="modal" id="invoice-modal" aria-hidden="true">
    <div class="modal-backdrop" onclick="cerrarFactura()"></div>
    <section class="modal-card invoice-card" role="dialog" aria-modal="true" aria-labelledby="invoice-title">
      <button class="modal-close" type="button" onclick="cerrarFactura()" aria-label="Cerrar formulario">&times;</button>
      <span class="eyebrow">Datos de facturación</span>
      <h2 id="invoice-title">Finaliza tu compra</h2>
      <p>Ingresa los datos que aparecerán en tu factura.</p>
      <form id="invoice-form" method="post" action="">
        <label for="cedula">Cédula</label>
        <input type="text" id="cedula" name="cedula" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" placeholder="Ej. 1723456789" required>
        <label for="nombre">Nombre completo</label>
        <input type="text" id="nombre" name="nombre" minlength="3" placeholder="Ej. Jhadith Andrade" required>
        <div class="invoice-total"><span>Total a pagar</span><strong id="invoice-total">$0.00</strong></div>
        <input type="hidden" id="invoice-total-value" name="total" value="0">
        <button id="confirm-button" type="submit">Confirmar compra</button>
        <button id="download-button" class="secondary-button" type="button" hidden onclick="descargarFactura()">Descargar factura</button>
      </form>
    </section>
  </div>

  <footer class="footer">
    <div class="brand footer-brand"></div>
    <p>Productos que no necesitaras nunca en tu vida, como esta pagina</p>
    <small>&copy; 2026 Jhadith Store. Todos los derechos reservados.</small>
  </footer>
</body>
</html>
