<?php

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql -> execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);


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
   <div class="container min-vh-100">
   <div class="row">
      <?php foreach ($resultado as $row) { ?>
        <div class="col-sm-8 col-md-6 col-lg-4 col-xl-3">
            <div class="card">
                <?php
                $id = $row['id'];
                $imagen = "images/productos/" . $id . "/principal.jpg";

                if(!file_exists($imagen)){
                    $imagen = "images/no-photo.jpg";
                }
                ?>

                <img class="card-img-top" src="<?php echo $imagen; ?>">
                <div class="card-body">
                    <h5 class="card-text"><?php echo $row['nombre']; ?></h5>
                    <p class="card-text">$<?php echo $row['precio']; ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" class="btn btn-primary">Detalles</a>
                        </div>
                        <button class="btn btn-outline-success" type="button" onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>')">Agregar al carrito</button>
                    </div>
                </div>
            </div>
        </div>
      <?php } ?>
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
  
  <footer class="bg-dark text-white">
      <div class="container">
          <nav class="row align-items-center">
              <!--Logo-->
              <a href="index.php" class="col-sm-8 col-md-6 col-lg-3 col-xl-3 text-reset text-uppercase d-flex align-items-center">
              <img class="img-logo mr-2" src="images/logo_eyeblack.png" alt="Logo Eyeblack" height="90">
              Eyeblack Store
              </a>
              <!--Copyright-->
              <a class="col-sm-8 col-md-6 col-lg-3 col-xl-3 text-reset text-uppercase d-flex align-items-center">
              Copyright Eyeblack Store - 2022. All rights reserved.
              </a>
              <!--Copyright-->
              <a class="col-sm-8 col-md-6 col-lg-3 col-xl-3 text-reset text-uppercase d-flex align-items-center">
              Created by Lorenzo Píccolo
              </a>
              <!--Redes-->
              <a href="https://www.instagram.com/eyeblackstore/?hl=es-la" class="col-sm-8 col-md-6 col-lg-3 col-xl-3 text-reset text-uppercase d-flex align-items-center">
              Instagram
              </a>
          </nav>
      </div>
  </footer>
</html>