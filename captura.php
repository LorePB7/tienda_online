<?php 
require 'config/config.php';
require 'config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Datos de compra</title>
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
  
<?php 
$db = new Database();
$con = $db->conectar();

$payment = $_GET['payment_id'];
$status = $_GET['status'];
$payment_type = $_GET['payment_type'];
$order_id = $_GET['merchant_order_id'];

echo "<h3>Pago exitoso</h3>";
echo "<h4>Los datos de su compra son:</h4>";

echo '<b>ID de su compra: </b>' . $payment.'<br>';
echo '<b>Status de su compra: </b>' .$status.'<br>';
echo '<b>Método de pago: </b>' .$payment_type.'<br>';
echo '<b>Número de orden: </b>' .$order_id.'<br>';

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos']: null;

if($productos != null){
    foreach($productos as $clave => $cantidad){
  
        $sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE id=? AND activo=1");
        $sql -> execute([$clave]);
        $row_prod = $sql->fetch(PDO::FETCH_ASSOC);

        $precio = $row_prod['precio'];
        $descuento = $row_prod['descuento'];
        $precio_desc = $precio - (($precio * $descuento) / 100);

        $sql_insert = $con->prepare("INSERT INTO detalle_compra (id_compra, id_producto, nombre, precio, cantidad) VALUES (?,?,?,?,?)");
        $sql_insert->execute([$payment ,$clave, $row_prod['nombre'], $precio_desc, $cantidad]);
    }
  }

unset($_SESSION['carrito']);

?>

<button class="btn btn-success" type="button" onclick="window.print()">Imprimir</button>

</body>
</html>