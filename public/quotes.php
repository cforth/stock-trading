<?php 
    // enable sessions
  session_start();
  $prompt = " ";
  
  if(!isset($_SESSION["authenticated"])) {
    $host = $_SERVER["HTTP_HOST"];
    $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
    header("Location: http://$host$path/home.php");
    exit;
  }

   require '../module/header.php';
?>

    <h2>股票行情</h2>
    
    <p>建设中...</p>

<?php
  require '../module/footer.php';
?>
