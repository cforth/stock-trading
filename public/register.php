<?php
    // enable sessions
    session_start();
    $prompt = " ";

    // if username and password were submitted, check them
    if (isset($_POST["user"]) && isset($_POST["pass"]))
    {

        // connect to database
        if (($connection = mysql_connect("localhost", "cf", "123456")) === false)
            die("Could not connect to database");

        // select database
        if (mysql_select_db("cfstocks", $connection) === false)
            die("Could not select database");

        $name = sprintf("SELECT 1 FROM users WHERE user='%s'", mysql_real_escape_string($_POST["user"]));

        $result = mysql_query($name);
        if (mysql_num_rows($result) == 0) {
          // prepare SQL
          $sql = sprintf("INSERT INTO `users`(`user`, `pass`) VALUES ('%s' , AES_ENCRYPT('%s', '%s'))",
                       mysql_real_escape_string($_POST["user"]),
                       mysql_real_escape_string($_POST["pass"]),
                       mysql_real_escape_string($_POST["pass"]));

          // execute query
          $result = mysql_query($sql);
          if ($result === false) {
            die("Could not query database");
          } else {
            // remember that user's logged in
            $_SESSION["authenticated"] = true;
            $_SESSION["user"] = $_POST["user"];

            // redirect user to home page, using absolute path, per
            // http://us2.php.net/manual/en/function.header.php
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: http://$host$path/home.php");
            exit;
          }
        } else {
          $prompt = "用户名已存在，请重新输入";
        }
    
    }

  require '../module/header.php';
?>

    <form action="<?php  print($_SERVER["PHP_SELF"]) ?>" method="post">
      <table>
        <tr>
          <td>用户名:</td>
          <td>
            <input name="user" type="text"></td>
        </tr>
        <tr>
          <td>密码:</td>
          <td><input name="pass" type="text"></td>
        </tr>
        <tr>
          <td></td>
          <td><input type="submit" value="注册"></td>
        </tr>
      </table>      
    </form>

    <p><?php print($prompt) ?></p>
    
    <ul>
      <li><a href="home.php">回主页面</a></li>
    </ul>

<?php
  require '../module/footer.php';
?>
