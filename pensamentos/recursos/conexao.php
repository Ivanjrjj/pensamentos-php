<?php
  error_reporting(0);
  
  try {
    $conexao = new PDO("mysql:host=localhost;port=3306;dbname=pensamentos", "root", "");
  } catch (\Throwable $th) {
    echo '<script>alert("Falha ao conectar com o banco de dados: ' . $th->getMessage() . ' ")</script>';
  }
?>