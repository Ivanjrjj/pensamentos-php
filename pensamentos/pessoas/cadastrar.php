<?php
  include '../recursos/conexao.php';

  try {
    session_start();

    if (isset($_SESSION['email'])) {
      header("location: ../postagens/lista.php");
    }

    if (isset($_POST['nome'], $_POST['email'], $_POST['senha'])) {
      if (strlen($_POST['senha']) < 6) {
        throw new Exception('Senha muito fraca! Insira uma senha de pelo menos 6 caracteres');
      }

      if (strlen($_POST['nome']) == 0) {
        throw new Exception('O nome não pode ser vazio!');
      }

      if (strlen($_POST['email']) == 0) {
        throw new Exception('O e-mail não pode ser vazio!');
      }

      if (!str_contains($_FILES['foto']['type'], 'image')) {
        throw new Exception('Arquivo inválido! Selecione uma imagem');
      }

      if ($_FILES['foto']['size'] > (1024 * 1024 * 2)) {
        throw new Exception('Imagem muito grande! Reduza o tamanho da imagem ou escolha outra imagem');
      }

      $foto = file_get_contents($_FILES['foto']['tmp_name']);

      $consulta = $conexao->prepare("INSERT INTO pessoa (nome, email, senha, foto) VALUES (:nome, :email, :senha, :foto)");
      $consulta->bindParam(':nome', $_POST['nome']);
      $consulta->bindParam(':email', $_POST['email']);
      $consulta->bindParam(':senha', sha1($_POST['senha'])); // Criptografia
      $consulta->bindParam(':foto', $foto);
      $consulta->execute();
      $_SESSION['nome'] = $_POST['nome'];
      $_SESSION['email'] = $_POST['email'];
      $_SESSION['codigo'] = $conexao->lastInsertId();
      $_SESSION['foto'] = $foto;
      header("location: ../postagens/lista.php");
    }
  } catch (\Throwable $th) {
    if ($th->getCode() == 23000) {
      echo '<script>alert("Falha ao cadastrar: o e-mail \"' . $_POST['email'] . '\" já está cadastrado!")</script>';
    } else {
      echo '<script>alert("Erro: ' . $th->getMessage() . ' ")</script>';
    }
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
  <form action="cadastrar.php" method="POST" enctype="multipart/form-data">
    <h4>Cadastre sua conta</h4>
    <input type="text" name="nome" placeholder="Nome" required><br>
    <input type="email" name="email" placeholder="E-mail" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>
    <input type="file" name="foto" accept="image/*" required><br>
    <input id="submit-cadastrar" class="submits" type="submit" value="Cadastrar conta">
  </form>

  <a href="..">Voltar para o início</a>
</body>
</html>