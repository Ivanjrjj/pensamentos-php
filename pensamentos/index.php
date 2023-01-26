<?php
  error_reporting(0);

  session_start();
  
  if (isset($_SESSION['email'])) {
    header("location: postagens/lista.php");
  }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="recursos/imagens/brain.png" type="image/x-icon">
  <link rel="stylesheet" href="recursos/estilos/geral.css">
  <title>pensamentos</title>
</head>
<body>
  <img src="recursos/imagens/brain.png" alt="Logo da rede de pensamentos">
  <h2>#pensamentos</h2>
  <a class="links" id="link-acessar" href="pessoas/acessar.php">Acessar minha conta</a>
  <a class="links" id="link-cadastrar" href="pessoas/cadastrar.php">Cadastrar conta</a>
</body>
</html>