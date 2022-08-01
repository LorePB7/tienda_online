<?php

require 'config/config.php';
require 'config/database.php';
require 'vendor/autoload.php';

MercadoPago\SDK::setAccessToken('TEST-8221914580112236-042519-c2e2c4462e8a9d4bb2171aa8533e1bb7-292116879');

$preference = new MercadoPago\Preference();
$productos_mp = array();

$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos']: null;

$lista_carrito = array();

if($productos != null){
  foreach($productos as $clave => $cantidad){

      $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo=1");
      $sql -> execute([$clave]);
      $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
  }
} else {
  header("Location: index.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eyeblack Store</title>

    <!--Script MercadoPago -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <!--Boostrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" 
    crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">
    <link href="css/main.scss" rel="stylesheet">
</head>

<body>
  <!--Barra de navegación-->
  <header>
    <div class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
      <div class="container">
       <a href="index.php" class="navbar-brand">
         <img class="logotipo" src="images/logo_eyeblack.png" width="auto" height="80" alt="Logo Eyeblack">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarHeader">
           <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <a href="index.php" class="nav-link active">Catálogo</a>
                </li>
                <li class="nav-item">
                  <a href="nosotros.php" class="nav-link">Nosotros</a>
                </li>
            </ul>
            <a href="checkout.php" class="btn btn-primary">
              Carrito<span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
            </a>
        </div>
      </div>
   </div>
  </header>

  <!--Contenido-->
  <main>
    <div class="container">

        <div class="row">
            <div class="col-6">
                <h4>Detalles de Pago</h4>
                <div class="checkout-btn"></div>
            </div>

        <div class="col-6">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($lista_carrito == null){
                      echo '<tr><td colspan="5" class="text-center"><b>No hay productos en esta lista</b></td></tr>';
                    } else {

                        $total = 0;
                        foreach($lista_carrito as $producto){
                          $_id = $producto['id'];
                          $nombre = $producto['nombre'];
                          $precio = $producto['precio'];
                          $descuento = $producto['descuento'];
                          $cantidad = $producto['cantidad'];
                          $precio_desc = $precio - (($precio * $descuento) / 100);
                          $subtotal = $cantidad * $precio_desc;
                          $total += $subtotal; 

                          $item = new MercadoPago\Item();
                          $item->id = $_id;
                          $item->title = $nombre;
                          $item->quantity = $cantidad;
                          $item->unit_price = $precio_desc;
                          $item->currency_id = 'ARS';

                          array_push($productos_mp, $item);
                          unset($item);
                    ?>
                      
                    <tr>
                        <td><?php echo $nombre; ?></td>
                        <td>
                            <div class="subtotal_<?php echo $_id ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>

                    <tr>
                        <td colspan="2">
                            <p class="h3 text-end" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                        </td>
                    </tr>
                </tbody>
                <?php } ?>
            </table>
        </div>
        </div>
    </div>
    </div>
  </main>

  <?php  
  
  $preference->items = $productos_mp;
  $preference->back_urls = array(
    "success" => "http://localhost/tienda_online/captura.php",
    "failure" => "http://localhost/tienda_online/fallo.php",
  );
  
  $preference->auto_return = "approved";
  $preference->binary_mode = true;
  
  $preference->save();
  
  ?>

  <!--Boostrap Bundle-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" 
  integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" 
  crossorigin="anonymous">
  </script>

<script>
        const mp = new MercadoPago('TEST-d12b8954-eb42-49a5-a133-2c6f2b73c5b6', {
            locale: 'es-AR'
        });

        mp.checkout({
            preference: {
                id: '<?php echo $preference->id; ?>'
            },
            render : {
                container: '.checkout-btn',
                label: 'Pagar con Mercado Pago'
            }
        });

    </script>
    
</body>

      <!-- Footer -->
  
  <footer class="bg-dark text-white fixed-bottom">
      <div class="container">
          <nav class="row">
              <!--Logo-->
              <a href="index.php" class="col-3 text-reset text-uppercase d-flex align-items-center">
              <img class="img-logo mr-2" src="images/logo_eyeblack.png" alt="Logo Eyeblack" height="90">
              Eyeblack Store
              </a>
              <!--Copyright-->
              <a class="col-3 text-reset text-uppercase d-flex align-items-center">
              Copyright Eyeblack Store - 2022. All rights reserved.
              </a>
              <!--Copyright-->
              <a class="col-3 text-reset text-uppercase d-flex align-items-center">
              Created by Lorenzo Píccolo
              </a>
              <!--Redes-->
              <a href="https://www.instagram.com/eyeblackstore/?hl=es-la" class="col-3 text-reset text-uppercase d-flex align-items-center">
              Instagram
              </a>
          </nav>
      </div>
  </footer>


</html>