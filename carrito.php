<?php
require_once "config/conexion.php";
require_once "config/config.php";
require_once "tcpdf/tcpdf.php";

// Iniciar la sesión
session_start();

if (isset($_GET['generate_pdf'])) {
    generatePDF();
}

function generatePDF() {
    global $conexion;

    // Obtener productos desde la base de datos
    $productQuery = mysqli_query($conexion, "SELECT * FROM productos");
    $products = mysqli_fetch_all($productQuery, MYSQLI_ASSOC);

    // Crear un archivo PDF
    $pdfFilePath = 'Factura.pdf';
    $pdf = new TCPDF();
    $pdf->SetFont('dejavusans', '', 12);

    // Establecer la información del documento
    $pdf->SetCreator('Your Creator');
    $pdf->SetAuthor('Your Author');
    $pdf->SetTitle(' Productos "Tienda Online"');
    $pdf->SetHeaderData('', 0, 'Productos', '');

    // Agregar una página
    $pdf->AddPage();

    // Escribir el encabezado
    $pdf->Cell(0, 10, "Tienda Online", 0, 1, 'C');

    // Escribir encabezados de columnas
    $pdf->Cell(30, 10, 'ID', 1);
    $pdf->Cell(70, 10, 'Producto', 1);
    $pdf->Cell(30, 10, 'Precio', 1);
    $pdf->Ln();

    $total = 0;

    // Obtener productos desde localStorage
    if (isset($_POST['data']) && !empty($_POST['data'])) {
        $productIds = json_decode($_POST['data'], true);

        foreach ($productIds as $productId) {
            // Encontrar el producto en el array
            $product = array_filter($products, function ($item) use ($productId) {
                return $item['id'] == $productId;
            });

            if ($product) {
                $product = reset($product);

                // Agregar una fila al PDF
                $pdf->Cell(30, 10, $product['id'], 1);
                $pdf->Cell(70, 10, $product['nombre'], 1);
                $pdf->Cell(30, 10, $product['precio'], 1);
                $pdf->Ln();

                $total += $product['precio'];
            }
        }
    }

    // Agregar una fila para el total
    $pdf->Cell(100, 10, 'Total', 1);
    $pdf->Cell(30, 10, $total, 1);

    // Salida del PDF
    $pdf->Output($pdfFilePath, 'D');

    // Salir del script
    exit;
}
?>

<!-- Pagina renderiza para la vista del carrito -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Carrito de Compras</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" /> -->
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link href="assets/css/estilos.css" rel="stylesheet" />
</head>

<body>
    <!-- Navigation-->
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.html">Variedades Monada</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="ropa.php"> Regresar </a>
            </div>
        </nav>
    </div>
    <!-- Header-->
    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">Carrito</h1>
                <p class="lead fw-normal text-white-50 mb-0">Tus Productos Agregados.</p>
            </div>
        </div>
    </header>
    <section class="py-5">
        <div class="container px-4 px-lg-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody id="tblCarrito">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-5 ms-auto">
                    <h4>Total a Pagar: <span id="total_pagar">$ 0.00</span></h4>
                    <div class="d-grid gap-2">
                        <div id="paypal-button-container"></div>
                        <button class="btn btn-warning" type="button" id="btnVaciar">Vaciar Carrito</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Your Website 2021</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&locale=<?php echo LOCALE; ?>"></script>
    <script src="assets/js/scripts.js"></script>
    <script>
        mostrarCarrito();

        function mostrarCarrito() {
            if (localStorage.getItem("productos") != null) {
                let array = JSON.parse(localStorage.getItem('productos'));
                if (array.length > 0) {
                    $.ajax({
                        url: 'ajax.php',
                        type: 'POST',
                        async: true,
                        data: {
                            action: 'buscar',
                            data: array
                        },
                        success: function(response) {
                            console.log(response);
                            const res = JSON.parse(response);
                            let html = '';
                            res.datos.forEach(element => {
                                html += `
                            <tr>
                                <td>${element.id}</td>
                                <td>${element.nombre}</td>
                                <td>${element.precio}</td>
                                <td>1</td>
                                <td>${element.precio}</td>
                            </tr>
                            `;
                            });
                            $('#tblCarrito').html(html);
                            $('#total_pagar').text(res.total);
                            paypal.Buttons({
                                style: {
                                    color: 'blue',
                                    shape: 'pill',
                                    label: 'pay'
                                },
                                createOrder: function(data, actions) {
                                    // This function sets up the details of the transaction, including the amount and line item details.
                                    return actions.order.create({
                                        purchase_units: [{
                                            amount: {
                                                value: res.total
                                            }
                                        }]
                                    });
                                },
                                onApprove: function(data, actions) {
                                    // This function captures the funds from the transaction.
                                    return actions.order.capture().then(function(details) {
                                        // This function shows a transaction success message to your buyer.
                                        alert('Transaction completed by ' + details.payer.name.given_name);
                                    });
                                }
                            }).render('#paypal-button-container');
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
            }
        }
    </script>
</body>

</html>