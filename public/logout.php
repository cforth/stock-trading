<?php 
    // enable sessions
    session_start();

    // delete cookies, if any
    setcookie("user", "", time() - 3600);
    setcookie("pass", "", time() - 3600);

    // log user out
    setcookie(session_name(), "", time() - 3600);
    session_destroy();
  require '../module/header.php'
?>

    <h1>您已经登出!</h1>
    <a href="home.php">回主页面</a>

<?php
  require '../module/footer.php';
?>
