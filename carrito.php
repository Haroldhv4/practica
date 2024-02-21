<?php
require_once "config/conexion.php";
require_once "config/config.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
    <link rel="stylesheet" href="estilos/css.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Carrito de Compras</title>
</head>
<body>
    <section id="inicio">
        <div class="contenido">
            <header>
                <div class="contenido-header">
                    <h1>/VM/</h1>
                    <nav id="nav" class="">
                        <ul id="links">
                            <li><a href="index.php" class="seleccionado" onclick="seleccionar(this)">INICIO</a></li>
                            <li><a href="#sobremi" onclick="seleccionar(this)">NOSOTROS</a></li>
                            <li><a href="ropa.html">PRODUCTOS</a></li>
                            <li><a href="visionymision.html" onclick="seleccionar(this)">VISION</a></li>
                            <li><a href="cliente.html" onclick="seleccionar(this)" > CLIENTES </a></li>
                        </ul>
                    </nav>

                    <!-- Icono del menu responsive -->
                    <div id="icono-nav" onclick="responsiveMenu()">
                        <i class="fa-solid fa-bars"></i>
                    </div>
                    <div class="redes">
                        <a href="#"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram-square"></i></a>
                    </div>
                </div>
            </header>
            <br>    
            <br>
            <br>
            <br>
            <br>
            <p style="text-align: center; color: white;" class="bienvenida"> BIENVENIDO/A</p>
            <h2 style="color: white;"> Almacen "Variedades Monada"</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody style="color: white;" id="carrito-body">
            <!-- Aquí se mostrarán los productos del carrito -->
        </tbody>
    </table>
    <br>
    <br>
    <div style="position: absolute; bottom: 0; right: 0;" class="col-md-4">
        <p style="color: white; font-size: 20px; display: inline-block;">Total: <span id="total"></span></p>
        <div id="paypal-button-container"></div>
    </div>
</section>
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&locale=<?php echo LOCALE; ?>"></script>
<script>
    paypal.Buttons().render('#paypal-button-container');
</script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Si la página actual es la del carrito, muestra los productos del carrito
            if (window.location.href.includes("carrito.html")) {
                showCart();
            }
        });

        function showCart() {
            console.log("showcard se está ejecutando");
            let carritoBody = document.getElementById('carrito-body');
            let totalElement = document.getElementById('total');

            // Recupera el carrito almacenado en localStorage
            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

            // Limpia el contenido actual de la tabla
            carritoBody.innerHTML = '';

            // Recorre los elementos del carrito y los agrega a la tabla
            carrito.forEach(item => {
                let row = carritoBody.insertRow();
                let cellProducto = row.insertCell(0);
                let cellPrecio = row.insertCell(1);

                cellProducto.innerHTML = item.producto;
                cellPrecio.innerHTML = `$${item.precio.toFixed(2)}`;
            });

            // Calcula el total después de haber recorrido todos los elementos
            let total = carrito.reduce((acc, item) => acc + item.precio, 0);

            // Actualiza el total
            totalElement.innerHTML = `$${total.toFixed(2)}`;
        }
    </script>
</body>
</html>