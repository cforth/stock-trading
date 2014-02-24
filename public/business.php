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

    <h2>交易</h2>

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
          <td><input type="submit"  class="push" value="委托"></td>
        </tr>
      </table>      
    </form>  
    
    <p><?php print($prompt) ?></p>
    

    <h2>历史委托明细:</h2>
    <table>
    <tr>
      <td>委托合同号</td>
      <td>委托方向</td>
      <td>股票代码</td>
      <td>委托数量</td>
      <td>委托价格</td>
      <td>委托时间</td>
    </tr>
    <?php
      $sql_result = mysql_query(sprintf("SELECT 1 FROM users WHERE user='%s'", $_SESSION["user"]));
      
      if (mysql_num_rows($sql_result) == 1) {
        $sql_user = mysql_fetch_array(mysql_query(sprintf("SELECT id FROM users WHERE user='%s'", $_SESSION["user"])));
        $lswt_array = mysql_query(sprintf("SELECT * FROM wtmx WHERE user_id = '%d'", $sql_user["id"]));
        while($lswt_rows = mysql_fetch_array($lswt_array)) {
          print("<tr><td>" . $lswt_rows["id"] . "</td><td>" . $lswt_rows["wtfx"] . "</td><td>" . $lswt_rows["gpdm"] . "</td><td>" . $lswt_rows["wtsl"] . "</td><td>" . $lswt_rows["wtjg"] . "</td><td>". $lswt_rows["wtmx_time"] . "</td></tr>");
        }
      }
    ?>
  </table>

<?php
  require '../module/footer.php';
?>
