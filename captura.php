<?php 
require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

$payment = $_GET['payment_id'];
$status = $_GET['status'];
$payment_type = $_GET['payment_type'];
$order_id = $_GET['merchant_order_id'];

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

</body>
</html>