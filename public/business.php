<?php 
    // enable sessions
  session_start();
  $prompt = " ";
  
  if(isset($_SESSION["authenticated"])) {
    // connect to database
    if (($connection = mysql_connect("localhost", "cf", "123456")) === false)
      die("Could not connect to database");
      // select database
    if (mysql_select_db("cfstocks", $connection) === false)
      die("Could not select database");
    
    if (isset($_POST["gpdm"]) && isset($_POST["wtsl"]) && isset($_POST["wtjg"]) && isset($_POST["wtfx"])) {
      
      $sql_result = mysql_query(sprintf("SELECT 1 FROM users WHERE user='%s'", $_SESSION["user"]));
      
      if (mysql_num_rows($sql_result) == 1) {
        
        $sql_user = mysql_fetch_array(mysql_query(sprintf("SELECT id FROM users WHERE user='%s'", $_SESSION["user"])));
        $sql = sprintf("INSERT INTO `wtmx`(`user_id`, `gpdm`, `wtsl`, `wtjg`, `wtfx`) VALUES ('%d', '%s', '%d', '%f', '%s')",
                $sql_user['id'],
                mysql_real_escape_string($_POST["gpdm"]),
                mysql_real_escape_string($_POST["wtsl"]),
                mysql_real_escape_string($_POST["wtjg"]),
                mysql_real_escape_string($_POST["wtfx"]));
        $sql_insert = mysql_query($sql);
        if ($sql_insert === false) { 
          $prompt ="委托失败！";
        } else {
          $prompt = "委托成功！";
        }
      } else {
        die("无此用户");
      }
    }

  } else {
    $host = $_SERVER["HTTP_HOST"];
    $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
    header("Location: http://$host$path/home.php");
    exit;
  }

   require '../module/header.php';
?>

    <h1>交易</h1>

    <form action="<?php  print($_SERVER["PHP_SELF"]) ?>" method="post">
      <select name="wtfx">
        <option value="b">买入</option>
        <option value="s">卖出</option>
      </select>
      <table>
        <tr>
          <td>股票代码:</td>
          <td>
            <input name="gpdm" type="text"></td>
        </tr>
        <tr>
          <td>委托数量:</td>
          <td><input name="wtsl" type="text"></td>
        </tr>
        <tr>
          <td>委托价格:</td>
          <td><input name="wtjg" type="text"></td>
        </tr>
        <tr>
          <td></td>
          <td><input type="submit" value="委托"></td>
        </tr>
      </table>      
    </form>  
    
    <p><?php print($prompt) ?></p>
    
    <ul>
      <li><a href="home.php">回主页面</a></li>
    </ul>

    <h2>历史委托明细:</h2>
    <?php
      $sql_result = mysql_query(sprintf("SELECT 1 FROM users WHERE user='%s'", $_SESSION["user"]));
      
      if (mysql_num_rows($sql_result) == 1) {
        $sql_user = mysql_fetch_array(mysql_query(sprintf("SELECT id FROM users WHERE user='%s'", $_SESSION["user"])));
        $lswt_array = mysql_query(sprintf("SELECT * FROM wtmx WHERE user_id = '%d'", $sql_user["id"]));
        while($lswt_rows = mysql_fetch_array($lswt_array)) {
          print("<p>委托合同号:" . $lswt_rows["id"] . "  委托方向:" . $lswt_rows["wtfx"] . "  股票代码:" . $lswt_rows["gpdm"] . "  委托数量:" . $lswt_rows["wtsl"] . "  委托价格:" . $lswt_rows["wtjg"] . "  委托时间:". $lswt_rows["wtmx_time"] . "</p>");
        }
      }
    ?>

<?php
  require '../module/footer.php';
?>
