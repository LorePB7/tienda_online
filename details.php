<?php

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if($id == '' || $token == '') {
    echo 'Error al procesar la petición';
    exit;
} else {

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token == $token_tmp) {

        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
        $sql -> execute([$id]);
        if($sql->fetchColumn() > 0) {

            $sql = $con->prepare("SELECT nombre, descripción, precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
            $sql -> execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $descripcion = $row['descripción'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            $dir_images = 'images/productos/' . $id . '/';
            
            $rutaImg = $dir_images . 'principal.jpg';

            if(!file_exists($rutaImg)){
              $rutaImg = 'images/no-photo.jpg';
            }

            $imagenes = array();

            if(file_exists($dir_images)) {
            $dir = dir($dir_images);

            while(($archivo = $dir->read()) != false){
              if ($archivo != 'principal.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))){
                  $imagenes[] = $dir_images . $archivo;
              }
            }
            $dir->close();
          }
        }
    } else{
        echo 'Error al procesar la petición';
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eyeblack Store</title>

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
              <div class="col-md-6 order-md-1">
                  <div id="carouselImages" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">

                     <div class="carousel-item active">
                        <img src="<?php echo $rutaImg; ?>" class="d-block w-100">
                     </div>

                     <?php foreach ($imagenes as $img) { ?>
                     <div class="carousel-item">
                         <img src="<?php echo $img; ?>" class="d-block w-100">
                     </div>
                     <?php } ?>

                   </div>
                   <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Previous</span>
                    </button>
                   <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Next</span>
                    </button>
                  </div>

                  
              </div>
              <div class="col-md-6 order-md-2">
                  <h2><?php echo $nombre; ?></h2>

                  <?php if($descuento > 0) { ?>
                      <p><del><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></del></p>
                      <h2>
                          <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?>
                          <small class="text-success"><?php echo $descuento; ?>% de descuento</small>
                      </h2>


                  <?php }else { ?>

                  <h2><?php echo MONEDA . $precio; ?></h2>

                  <?php } ?>

                  <p class="lead">
                    <?php echo $descripcion; ?>
                  </p>

                  <div class="d-grid gap-3 col-10 mx-auto">
                      <button class="btn btn-primary" type="button">Comprar ahora</button>
                      <button class="btn btn-outline-primary" type="button" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">Agregar al carrito</button>
                  </div>
              </div>
          </div>
      </div>
  </main>

  <!--Boostrap Bundle-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" 
  integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" 
  crossorigin="anonymous">
  </script>

  <script>
    function addProducto(id, token){
        let url = 'clases/carrito.php'
        let formData = new FormData()
        formData.append('id', id)
        formData.append('token', token)

        fetch(url, {
          method: 'POST',
          body: formData,
          mode: 'cors'
        }).then(response => response.json())
        .then(data => {
              if(data.ok) {
              let elemento = document.getElementById("num_cart")
              elemento.innerHTML = data.numero
        }
        })
    }
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