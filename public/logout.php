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

    <h2>您已经登出!</h2>

<?php
  require '../module/footer.php';
?>
