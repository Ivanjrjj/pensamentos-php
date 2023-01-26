<?php
  try {
    session_start();
    include '../recursos/conexao.php';

    if (!isset($_SESSION['email'])) {
      header("location: ..");
    }

    if (isset($_GET['sair-da-conta'])) {
      session_destroy();
      header("location: ..");
    }

    if (isset($_POST['texto'])) {
      $consulta = $conexao->prepare("INSERT INTO postagem (texto, codigo_pessoa) VALUES (:texto, :codigo)");
      $consulta->bindParam(':texto', $_POST['texto']);
      $consulta->bindParam(':codigo', $_SESSION['codigo']);
      $consulta->execute();
    }

    if (isset($_GET['excluir'])) {
      $consulta = $conexao->prepare("DELETE FROM postagem WHERE codigo=:excluir AND codigo_pessoa=:codigo_pessoa");
      $consulta->bindParam(':excluir', $_GET['excluir']);
      $consulta->bindParam(':codigo_pessoa', $_SESSION['codigo']);
      $consulta->execute();
    }

    if (isset($_GET['excluir-conta'])) {
      $consulta = $conexao->prepare("DELETE FROM pessoa WHERE codigo=:codigo");
      $consulta->bindParam(':codigo', $_SESSION['codigo']);
      $consulta->execute();
      echo "<script>alert('Conta excluída com sucesso!')</script>";
      session_destroy();
      echo "<script>window.location.href='..'</script>";
    }

    $consulta = $conexao->query("SELECT * FROM pessoa, postagem WHERE pessoa.codigo=postagem.codigo_pessoa ORDER BY postagem.codigo DESC");
  } catch (\Throwable $th) {
    echo '<script>alert("Aconteceu um erro. Tente novamente mais tarde... Erro: ' . $th->getMessage() . ' ")</script>';
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
  <link rel="stylesheet" href="../recursos/estilos/lista.css">
  <title>pensamentos</title>
</head>
<body>
  <header>
    <?php echo '<img id="img-pessoa" src="data:image/*;base64,' . base64_encode($_SESSION['foto']) . ' " alt="Foto do usuário">' ?>
    <?php echo "<h4>$_SESSION[nome] - $_SESSION[email]</h4>" ?>
    <div id="links-conta">
      <a class="links" id="link-sair-da-conta" href="lista.php?sair-da-conta=true">Sair da conta</a>
      <a class="links" id="link-excluir-conta" href="lista.php?excluir-conta=true">Excluir conta</a>
    </div>
    <form action="lista.php" method="POST">
      <input id="texto" name="texto" type="text" placeholder="O que você está pensando?">
      <input id="btn-postar" type="submit" value="Postar">
    </form>
  </header>

  <main>
    <?php while($linha = $consulta->fetch()): ?>
      <article>
        <?php
          if ($_SESSION['codigo'] == $linha['codigo_pessoa']) {
            echo "<a href='lista.php?excluir=$linha[codigo]'><img class='img-excluir' src='../recursos/imagens/delete.png' alt='Excluir postagem'></a>";
          }
          echo '<img class="img-pessoa-postagem" src="data:image/*;base64,' . base64_encode($linha['foto']) . ' " alt="Foto do usuário na postagem">';
        ?>
        <div>
          <h4><?php echo $linha['nome'] ?></h4>
          <p><?php echo $linha['texto'] ?></p>
        </div>
      </article>
    <?php endwhile ?>
  </main>
  <script>
    var entrada = document.getElementById('texto');
    entrada.focus();
  </script>
</body>
</html>