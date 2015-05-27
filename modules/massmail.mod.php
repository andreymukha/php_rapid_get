<?php
  /* модуль массовой рассылки 1 */
if (!defined('RAPIDGET')) {
    die ("not load primary script");
}

if(count($_GET["files"]) < 1)
  {
    echo $langselectf.".<br><br>";
  }
else
  {
    ?>
      <form method="post">
      <input type="hidden" name="act" value="massmail_go">
      <?php
     echo count($_GET["files"])." file".(count($_GET["files"]) > 1 ? "s" : "").":<br>";
      for($i = 0; $i < count($_GET["files"]); $i++)
        {
          $file = $list[($_GET["files"][$i])];
          if (in_array(basename($file["name"]),$systemfile)) continue;
          ?>
          <input type="hidden" name="files[]" value="<?php echo $_GET["files"][$i]; ?>">
          <b><?php echo basename($file["name"]); ?></b><?php echo $i == count($_GET["files"]) - 1 ? "." : ",&nbsp"; ?>
          <?php
        }
      ?><br><br>
      <table align="center">
        <tr>
          <td>
           <?php echo $langemailf;?>s:&nbsp;<textarea name="emails" cols="30" rows="8"><?php if ($_COOKIE["email"]) echo $_COOKIE["email"]; ?></textarea>
          </td>
          <td>
            <input type="submit" value="Send">
          </td>
        </tr>
        <tr>
         <td>
            <input type="checkbox" name="del_ok" checked>&nbsp;<?php echo $langdelsubmit;?>
         </td>
        </tr>
        <tr>
         <td>
         </td>
        </tr>
        <tr>
          <table>
            <tr>
              <td>
                <input id="splitchkbox" type="checkbox" name="split" onClick="javascript:var displ=this.checked?'':'none';document.getElementById('methodtd2').style.display=displ;"<?php echo $_COOKIE["split"] ? " checked" : ""; ?>>&nbsp;<?php echo $langsplitbyparts;?>
              </td>
              <td>&nbsp;

              </td>
              <td id=methodtd2<?php echo $_COOKIE["split"] ? "" : " style=\"display: none;\""; ?>>
                <table>
                  <tr>
                    <td>
                      <?php echo $langmethod;?>&nbsp;<select name="method"><option value="tc"<?php echo $_COOKIE["method"] == "tc" ? " selected" : ""; ?>><?php echo $langtc;?></option><option value="rfc"<?php echo $_COOKIE["method"] == "rfc" ? " selected" : ""; ?>><?php echo $langrfc;?></option></select>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <?php $langpartsize;?>&nbsp;<input type="text" name="partSize" size="2" value="<?php echo ($_COOKIE["partSize"] ? $_COOKIE["partSize"] : 10); ?>">&nbsp;<?php echo $langmb;?>
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
