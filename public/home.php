<?php 
  // enable sessions
  session_start();
  require '../module/header.php';
?>
      <?php if (isset($_SESSION["authenticated"])) { ?>
        <h1>欢迎您, <?php print($_SESSION['user']) ?> !</h1>
        <br />
        <ul>
          <li><a href="logout.php">注销</a></li>
          <li><a href="business.php">交易</a></li>
        </ul>
      <?php } else { ?>
        <h1>请登录或注册新用户!</h1>
        <ul>
          <li><a href="login.php">登陆</a></li>
          <li><a href="register.php">注册</a></li>
        </ul>
      <?php } ?>

<?php
  require '../module/footer.php';
?>
