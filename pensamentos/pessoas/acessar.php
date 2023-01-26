<?php
  include '../recursos/conexao.php';
  
  try {
    session_start();

    if (isset($_SESSION['email'])) {
      header("location: ../postagens/lista.php");
    }

    if (isset($_POST['email'], $_POST['senha'])) {
      $consulta = $conexao->prepare("SELECT * FROM pessoa WHERE email=:email AND senha=:senha");
      $consulta->bindParam(':email', $_POST['email']);
      $consulta->bindParam(':senha', sha1($_POST['senha']));
      $consulta->execute();

      $resultado = $consulta->fetch();
      if (isset($resultado['email'])) {
        $_SESSION['nome'] = $resultado['nome'];
        $_SESSION['email'] = $resultado['email'];
        $_SESSION['codigo'] = $resultado['codigo'];
        $_SESSION['foto'] = $resultado['foto'];
        header("location: ../postagens/lista.php");
      } else {
        throw new Exception('E-mail ou senha inválidos!');
      }
    }
  } catch (\Throwable $th) {
    echo '<script>alert("Erro: ' . $th->getMessage() . ' ")</script>';
  }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../recursos/imagens/brain.png" type="image/x-icon">
  <link rel="stylesheet" href="../recursos/estilos/geral.css">
  <link rel="stylesheet" href="../recursos/estilos/pessoas.css">
  <title>pensamentos</title>
</head>
<body>
  <img src="../recursos/imagens/brain.png" alt="Logo da rede de pensamentos">
  <h2>#pensamentos</h2>
  <form action="acessar.php" method="POST">
    <h4>Acesse sua conta</h4>
    <input type="email" name="email" placeholder="E-mail" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>
    <input id="submit-acessar" class="submits" type="submit" value="Acessar minha conta">
  </form>

  <a href="..">Voltar para o início</a>
</body>
</html>