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

    <script>

        function validate()
        {
            if (!document.forms.business.gpdm.value.match(/^\d{6,6}$/))
            {
                alert("股票代码格式错误");
                return false;
            } else if (!document.forms.business.wtsl.value.match(/^([1-9][0-9]*)$/))
            {
                alert("股票数量格式错误");
                return false;
            } else if (!document.forms.business.wtjg.value.match(/^\d+(\.\d+)?$/))
            {
                alert("股票价格格式错误");
                return false;
            }
            return true;
        }

      </script>

    <h2>交易</h2>

    <form action="<?php  print($_SERVER["PHP_SELF"]) ?>" method="post" name="business" onsubmit="return validate();">
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
    <table class="wtmx">
    <tr class="wtmx"> 
      <td class="wtmx">委托合同号</td>
      <td class="wtmx">委托方向</td>
      <td class="wtmx">股票代码</td>
      <td class="wtmx">委托数量</td>
      <td class="wtmx">委托价格</td>
      <td class="wtmx">委托时间</td>
    </tr>
    <?php
      $sql_result = mysql_query(sprintf("SELECT 1 FROM users WHERE user='%s'", $_SESSION["user"]));
      
      if (mysql_num_rows($sql_result) == 1) {
        $sql_user = mysql_fetch_array(mysql_query(sprintf("SELECT id FROM users WHERE user='%s'", $_SESSION["user"])));
        $lswt_array = mysql_query(sprintf("SELECT * FROM wtmx WHERE user_id = '%d'", $sql_user["id"]));
        while($lswt_rows = mysql_fetch_array($lswt_array)) {
          print("<tr class=\"wtmx\"><td>" . $lswt_rows["id"] . "</td><td class=\"wtmx\">" . $lswt_rows["wtfx"] . "</td><td class=\"wtmx\">" . $lswt_rows["gpdm"] . "</td><td class=\"wtmx\">" . $lswt_rows["wtsl"] . "</td><td class=\"wtmx\">" . $lswt_rows["wtjg"] . "</td><td class=\"wtmx\">". $lswt_rows["wtmx_time"] . "</td></tr>");
        }
      }
    ?>
  </table>

<?php
  require '../module/footer.php';
?>
