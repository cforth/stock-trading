<?php 
  // enable sessions
  session_start();
  require '../module/header.php';
?>
    <h1>股票交易主界面</h1>
      <?php if (isset($_SESSION["authenticated"])) { ?>
        <p>欢迎您, <?php print($_SESSION['user']) ?> !</p>
        <br />
        <a href="logout.php">注销</a>
        <br />
        <a href="business.php">交易</a>
      <?php } else { ?>
        <b>请登录或注册新用户!</b>
        <ul>
          <li><a href="login.php">登陆</a></li>
          <li><a href="register.php">注册</a></li>
        </ul>
      <?php } ?>

<?php
  require '../module/footer.php';
?>
