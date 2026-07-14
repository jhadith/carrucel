$(function () {
  $("#slider").Thumbelina({
    $bwdBut: $("#slider .left"),
    $fwdBut: $("#slider .right"),
    orientation: "horizontal",
    step: 50,
  });

  $("#cantidad").on("input", Multiplicar);
});

function imagenes(img) {
  document.getElementById("big-img").src = img.src;

  document.querySelectorAll(".imgc").forEach(function (imagen) {
    imagen.classList.remove("selected");
  });
  img.classList.add("selected");

  let nombre = img.alt;
  switch (nombre) {
    case "cocaCola":
      mostrarProducto("Coca-Cola clásica", 2.0, "amarillo", "rojo", "verde");
      break;
    case "fanta":
      mostrarProducto("Fanta sabor naranja", 1.75, "verde", "verde", "verde");
      break;
    case "sprit":
      mostrarProducto("Sprite lima-limón", 1.5, "amarillo", "rojo", "verde");
      break;
    case "pepsi":
      mostrarProducto("Pepsi refrescante", 1.8, "amarillo", "rojo", "verde");
      break;
    case "nestea":
      mostrarProducto("Nestea sabor limón", 2.25, "amarillo", "rojo", "verde");
      break;
    default:
      mostrarProducto("Bebida Jhadith", 2.0, "amarillo", "rojo", "verde");
      break;
  }

  Multiplicar();
}

function mostrarProducto(descripcion, valor, color1, color2, color3) {
  document.getElementById("descripcion").value = descripcion;
  document.getElementById("valor").value = valor.toFixed(2);
  document.getElementById("semaforo1").src = "./img/" + color1 + ".png";
  document.getElementById("semaforo2").src = "./img/" + color2 + ".png";
  document.getElementById("semaforo3").src = "./img/" + color3 + ".png";
}

function Multiplicar() {
  let valor = parseFloat(document.getElementById("valor").value) || 0;
  let cantidad = parseInt(document.getElementById("cantidad").value) || 0;
  document.getElementById("total").value = "$" + (valor * cantidad).toFixed(2);
}
