<?php
  /* модуль отправки на ftp - 1 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}

if(count($_GET["files"]) < 1)
  {
    echo "<div id='x1' style='padding:10px; border: 1px dashed red;'> $langselectf </div>";
  }
else
  {
    ?>
      <form method="post">
      <input type=hidden name=act value="ftp_go">
      <?php echo $langfile;?><? echo count($_GET["files"]) > 1 ? "$langs" : ""; ?>:
      <?php
      for($i = 0; $i < count($_GET["files"]); $i++)
        {
          $file = $list[($_GET["files"][$i])];
          if (in_array(basename($file["name"]),$systemfile)) continue;  
          ?>
          <input type=hidden name="files[]" value="<?php echo $_GET["files"][$i]; ?>">
          <b><?php echo basename($file["name"]); ?></b><?php echo $i == count($_GET["files"]) - 1 ? "." : ",&nbsp"; ?>
          <?php
        }
      ?><br><br>
      <table align="center">
        <tr>
          <td>
            <table>
              <tr>
                <td>
                  <?php echo $langhost;?>
                </td>
                <td>
                  <input name=host id="host"<?php echo $_COOKIE["host"] ? " value=\"".$_COOKIE["host"]."\"" : ""; ?>>
                </td>
              </tr>
              <tr>
                <td>
                 <?php echo $langport;?>
                </td>
                <td>
                  <input name=port id="port"<?php echo $_COOKIE["port"] ? " value=\"".$_COOKIE["port"]."\"" : " value=\"21\""; ?> size="1">
                </td>
              </tr>
              <tr>
                <td>
                 <?php echo $langlogin;?>
                </td>
                <td>
                  <input name=login id="login"<?php echo $_COOKIE["login"] ? " value=\"".$_COOKIE["login"]."\"" : ""; ?> size="12">
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $langpass;?>
                </td>
                <td>
                  <input name=password id="password"<?php echo $_COOKIE["password"] ? " value=\"".$_COOKIE["password"]."\"" : ""; ?> size="12">
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $langdirectory;?>
                </td>
                <td>
                  <input name=dir id="dir"<?php echo $_COOKIE["dir"] ? " value=\"".$_COOKIE["dir"]."\"" : " value=\"/\""; ?>  size="23">
                </td>
              </tr>
              <tr>
                <td>
                  <input type=checkbox class="checkbox" name=del_ok>&nbsp;<?php echo $langdelsuccupload;?>
                </td>
              </tr>
            </table>
            </td>
          <td>&nbsp;

          </td>
          <td>
            <table>
              <tr align="center">
                <td>
                  <input type=submit value="<?php echo $langupload2;?>">
                </td>
              </tr>
              <tr align="center">
                <td>
                  <?php echo $langoption;?>
                </td>
              </tr>
              <tr align="center">
                <td>
                  <script language="JavaScript">
                  function setFtpParams() {
                    setParam("host"); setParam("port"); setParam("login");
                    setParam("password"); setParam("dir");
                    document.cookie = "ftpParams=1";
                    document.getElementById("hrefSetFtpParams").style.color = "#808080";
                    document.getElementById("hrefDelFtpParams").style.color = "#0000FF";
                  }

                  function delFtpParams() {
                    deleteCookie("host"); deleteCookie("port"); deleteCookie("login");
                    deleteCookie("password"); deleteCookie("dir"); deleteCookie("ftpParams");
                    document.getElementById("hrefSetFtpParams").style.color = "#0000FF";
                    document.getElementById("hrefDelFtpParams").style.color = "#808080";
                  }

                  function setParam(param) {
                    document.cookie = param + "=" + document.getElementById(param).value;
                  }

                  document.write(
                    '<a href="javascript:setFtpParams();" id="hrefSetFtpParams" style="color: ' + (getCookie('ftpParams') == 1 ? '#808080' : '#0000FF') + ';"><?php echo $langcopyfiles;?></a> | ' +
                    '<a href="javascript:delFtpParams();" id="hrefDelFtpParams" style="color: ' + (getCookie('ftpParams') == 1 ? '#0000FF' : '#808080') + '";"><?php echo $langmovefiles;?></a>'
                  );
                  </script>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      </form>
    <?php
  }
?>
