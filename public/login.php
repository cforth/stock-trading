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

        // prepare SQL
        $sql = sprintf("SELECT 1 FROM users WHERE user='%s' AND pass=AES_ENCRYPT('%s', '%s')",
                       mysql_real_escape_string($_POST["user"]),
                       mysql_real_escape_string($_POST["pass"]),
                       mysql_real_escape_string($_POST["pass"]));

        // execute query
        $result = mysql_query($sql);
        if ($result === false)
            die("Could not query database");

        // check whether we found a row
        if (mysql_num_rows($result) == 1) {
            // remember that user's logged in
            $_SESSION["authenticated"] = true;
            $_SESSION["user"] = $_POST["user"];

            // redirect user to home page, using absolute path, per
            // http://us2.php.net/manual/en/function.header.php
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: http://$host$path/home.php");
            exit;
        } else {
          $prompt =  "用户名或密码错误！！";
        }
    }

  require '../module/header.php';
?>

    <br />
    <form action="<?php  print($_SERVER["PHP_SELF"]) ?>" method="post">
      <table>
        <tr>
          <td>用户名:</td>
          <td>
            <input name="user" type="text"></td>
        </tr>
        <tr>
          <td>密码:</td>
          <td><input name="pass" type="password"></td>
        </tr>
        <tr>
          <td></td>
          <td><input type="submit" class="push" value="登陆"></td>
        </tr>
      </table>      
      <p><?php print($prompt) ?></p>
    </form>
    
<?php
  require '../module/footer.php';
?>
