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

  <main>
    <div class="container">
      <div class="row border rounded border-dark" style="background-color: #bbb">
          <div class="col">
      <h2 class="text-center"><b>¿QUIENES SOMOS?</b></h2>
      <p class="text-center"><b>Eyeblack Store nace como un pequeño emprendimiento dedicado a la comercialización de indumentaria americana de alta calidad, brindándole a nuestros clientes una gran variedad de productos de las mejores ligas deportivas de Estados Unidos, tales como la NBA, MLB, NHL y NFL.</b></p>
          </div>
      </div>
    </div>
  </main>
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